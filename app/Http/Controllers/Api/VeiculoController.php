<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VeiculoCollection;
use App\Http\Resources\VeiculoResource;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VeiculoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $ownerid)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Veiculo::latest()
            ->where('ownerid', $ownerid);

        /** @var \Illuminate\Database\Eloquent\Collection $data */
        $data = $query->get();

        return $this->responseOk(new VeiculoCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $ownerid)
    {
        $validator = $this->validatorMake(self::ACTION_STORE, $request);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $veiculo = Veiculo::create([
            'marca_id' => $request->marca_id,
            'placa' => $request->placa,
            'modelo' => $request->modelo,
            'ano' => $request->ano,
            'ownerid' => $ownerid,
        ]);

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($ownerid, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = $this->findVeiculo($ownerid, $id);
        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ownerid, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = $this->findVeiculo($ownerid, $id);

        $validator = $this->validatorMake(self::ACTION_UPDATE, $request, $veiculo);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $veiculo->marca_id = $request->marca_id;
        $veiculo->placa = $request->placa;
        $veiculo->modelo = $request->modelo;
        $veiculo->ano = $request->ano;
        $veiculo->save();

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * @param Request $request
     * @param $ownerid
     * @param $id
     * @return mixed
     * @throws ValidationException
     *
     * @link https://medium.com/@nishantbhushan10/upload-image-using-rest-api-in-laravel-cb70d8ce0757
     */
    public function uploadImagem(Request $request, $ownerid, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = $this->findVeiculo($ownerid, $id);
        
        $validator = Validator::make($request->all(), [
            'figura' => 'required|image|mimes:jpg,png|max:2048'
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $uploadFolder = 'veiculos';
        $image = $request->file('figura');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        //$storage = Storage::disk('public');
        //return $storage->url($image_uploaded_path);

        if (!is_null($veiculo->figura)) {
            Storage::disk('public')->delete($veiculo->figura);
        }

        $veiculo->figura = $image_uploaded_path;
        $veiculo->save();

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($ownerid, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = $this->findVeiculo($ownerid, $id);
        $veiculo->delete();

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    private function validatorMake($action, $request, $veiculo = null)
    {
        $placaUniqueRule = Rule::unique('veiculos', 'placa');
        if ($action == self::ACTION_UPDATE) {
            $placaUniqueRule = $placaUniqueRule->ignore($veiculo->id);
        }

        if ($action == self::ACTION_UPDATE) {
            $placaUniqueRule = Rule::unique('veiculos', 'placa')
                ->where(function (Builder $query) use ($request, $veiculo) {
                    return $query
                        ->where('ownerid', $request->route('ownerid'))
                        ->where('id', '<>', $veiculo->id);
                });
        } else {
            $placaUniqueRule = Rule::unique('veiculos')->where(function ($query) use ($request) {
                return $query->where('ownerid', $request->route('ownerid'));
            });
        }

        return Validator::make($request->all(), [
            'marca_id' => ['required'],
            'placa' => ['required', 'size:7', $placaUniqueRule],
            'modelo' => ['required', 'max:30'],
            'ano' => ['required', 'int', 'min:1990', 'max:' . (date('Y') + 1)]
        ]);
    }

    /**
     * @param $ownerid
     * @param $id
     * @return Veiculo
     */
    private function findVeiculo($ownerid, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = Veiculo::findOrFail($id);

        if ($ownerid != $veiculo->ownerid) {
            throw (new ModelNotFoundException)->setModel(
                Veiculo::class, $id
            );
        }

        return $veiculo;
    }
}
