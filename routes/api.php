<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// ROTAS PÚBLICAS (auth)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// ROTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // CLIENTES
    Route::apiResource('clients', ClientController::class);
    Route::get('clients/{id}/vehicles', [ClientController::class, 'vehicles']);
    Route::post('clients/{id}/restore', [ClientController::class, 'restore']);

    // VEÍCULOS
    Route::apiResource('vehicles', VehicleController::class);
    Route::get('vehicles/{id}/services', [VehicleController::class, 'services']);

    // SERVIÇOS
    Route::apiResource('services', ServiceController::class);
    Route::post('services/{id}/start', [ServiceController::class, 'start']);
    Route::post('services/{id}/finish', [ServiceController::class, 'finish']);

    // LOGOUT (boa prática já deixar aqui)
    Route::post('logout', [AuthController::class, 'logout']);

    // DASHBOARD
    Route::get('dashboard', [DashboardController::class, 'index']);
});