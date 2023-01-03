<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;

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


Route::group(["prefix" => "v1/auth"], function(){

    Route::post('/login', [AuthController::class, "ingresar"]);
    Route::post('/registro', [AuthController::class, "registro"]);

    Route::group(["middleware" => "auth:sanctum"], function(){
        Route::get('/perfil', [AuthController::class, "perfil"]);
        Route::post('/logout', [AuthController::class, "salir"]);
    });
});


Route::get("/pedido/filtro", [PedidoController::class, "fitrar"]);

Route::group(["middleware" => "auth:sanctum"], function(){
    Route::apiResource("/usuario", UsuarioController::class);
    Route::apiResource("/categoria", CategoriaController::class);
    Route::apiResource("/producto", ProductoController::class);
    Route::apiResource("/cliente", ClienteController::class);
    Route::apiResource("/pedido", PedidoController::class);
});

