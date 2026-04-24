<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;

Route::apiResource('clients', ClientController::class);
//listando carro por cliente
Route::get('clients/{id}/vehicles', [ClientController::class, 'vehicles']);
Route::post('clients/{id}/restore', [ClientController::class, 'restore']);

//veiculos
Route::apiResource('vehicles', VehicleController::class);