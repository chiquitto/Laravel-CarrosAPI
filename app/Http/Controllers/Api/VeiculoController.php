<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MarcaResource;
use App\Http\Resources\VeiculoCollection;
use App\Http\Resources\VeiculoResource;
use App\Models\Marca;
use App\Models\Veiculo;
use Illuminate\Http\Request;
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
    public function index()
    {

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Veiculo::latest();

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
    public function store(Request $request)
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
        ]);

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = Veiculo::findOrFail($id);
        return $this->responseOk(new VeiculoResource($veiculo));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = Veiculo::findOrFail($id);

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
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Veiculo $veiculo */
        $veiculo = Veiculo::findOrFail($id);
        $veiculo->delete();

        return $this->responseOk(new VeiculoResource($veiculo));
    }

    private function validatorMake($action, $request, $veiculo = null)
    {
        $placaUniqueRule = Rule::unique('veiculos', 'placa');
        if ($action == self::ACTION_UPDATE) {
            $placaUniqueRule = $placaUniqueRule->ignore($veiculo->id);
        }

        return Validator::make($request->all(), [
            'marca_id' => ['required'],
            'placa' => ['required', 'size:7', $placaUniqueRule],
            'modelo' => ['required', 'max:30'],
            'ano' => ['required', 'int', 'min:1990', 'max:' . (date('Y')+1)]
        ]);
    }
}
