<?php

use App\Http\Controllers\CargoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\UserController;
use App\Models\Departamento;
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

Route::controller(CargoController::class)->prefix('cargos')->group(function () {
    Route::get('/', 'index');
});
Route::controller(DepartamentoController::class)->prefix('departamentos')->group(function () {
    Route::get('/', 'index');
});

Route::apiResource('usuarios', UserController::class);
