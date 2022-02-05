<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('{ownerid}/marca', \App\Http\Controllers\Api\MarcaController::class)
    ->where(['ownerid' => '[0-9a-zA-Z\-]{1,30}']);

Route::apiResource('{ownerid}/veiculo', \App\Http\Controllers\Api\VeiculoController::class)
    ->where(['ownerid' => '[0-9a-zA-Z\-]{1,30}']);

Route::post('{ownerid}/veiculo/{veiculo}/imagem', 'App\Http\Controllers\Api\VeiculoController@uploadImagem')
    ->where(['ownerid' => '[0-9a-zA-Z\-]{1,30}'])
    ->name('veiculo.imagem');
