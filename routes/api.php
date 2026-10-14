<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MotorcycleController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/motorcycles', [MotorcycleController::class, 'index']);
Route::get('/motorcycles/{motorcycle}', [MotorcycleController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me/motorcycles', [MotorcycleController::class, 'mine']);
    Route::post('motorcycles', [MotorcycleController::class, 'store']);
    Route::patch('motorcycles/{motorcycle}', [MotorcycleController::class, 'update']);
    Route::delete('motorcycles/{motorcycle}', [MotorcycleController::class, 'destroy']);
});
