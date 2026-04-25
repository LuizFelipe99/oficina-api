<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;

Route::apiResource('clients', ClientController::class);
//listando carro por cliente
Route::get('clients/{id}/vehicles', [ClientController::class, 'vehicles']);
Route::post('clients/{id}/restore', [ClientController::class, 'restore']);

//veiculos
Route::apiResource('vehicles', VehicleController::class);
Route::get('vehicles/{id}/services', [VehicleController::class, 'services']);

//serviços
Route::apiResource('services', ServiceController::class);
Route::post('services/{id}/start', [ServiceController::class, 'start']);
Route::post('services/{id}/finish', [ServiceController::class, 'finish']);