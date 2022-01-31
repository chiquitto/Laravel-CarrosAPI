<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MarcaCollection;
use App\Http\Resources\MarcaResource;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * @link https://codelapan.com/post/how-to-create-a-crud-rest-api-in-laravel-8-with-sanctum
 * @link https://terminalroot.com.br/2021/04/como-criar-uma-api-com-laravel-8.html
 * @link https://blog.pusher.com/build-rest-api-laravel-api-resources/
 */
class MarcaController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Marca::latest();

        /** @var \Illuminate\Database\Eloquent\Collection $data */
        $data = $query->get();

        return $this->responseOk(new MarcaCollection($data));
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

        $marca = Marca::create([
            'marca' => $request->marca
        ]);

        return $this->responseOk(new MarcaResource($marca));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Marca $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Marca $marca */
        $marca = Marca::findOrFail($id);
        return $this->responseOk(new MarcaResource($marca));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Marca $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** @var Marca $marca */
        $marca = Marca::findOrFail($id);

        $validator = $this->validatorMake(self::ACTION_UPDATE, $request, $marca);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $marca->marca = $request->marca;
        $marca->save();

        return $this->responseOk(new MarcaResource($marca));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Marca $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Marca $marca */
        $marca = Marca::findOrFail($id);
        $marca->delete();

        return $this->responseOk(new MarcaResource($marca));
    }

    private function validatorMake($action, $request, $marca = null)
    {
        $marcaUniqueRule = Rule::unique('marcas');
        if ($action == self::ACTION_UPDATE) {
            $marcaUniqueRule = $marcaUniqueRule->ignore($marca->id);
        }

        return Validator::make($request->all(), [
            'marca' => [
                'required',
                'string',
                'max:15',
                $marcaUniqueRule,
                //Rule::exists('marca')->where(function ($query) {
                //    return $query->where('account_id', 1);
                //}),
            ],
        ]);
    }
}
