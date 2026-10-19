<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MotorcycleController;
use App\Http\Controllers\Api\MotorcycleImageController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReferenceController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/motorcycles', [MotorcycleController::class, 'index']);
Route::get('/motorcycles/{motorcycle}', [MotorcycleController::class, 'show']);
Route::get('/brands', [ReferenceController::class, 'brands']);
Route::get('/categories', [ReferenceController::class, 'categories']);
Route::get('/cities', [ReferenceController::class, 'cities']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me/motorcycles', [MotorcycleController::class, 'mine']);
    Route::post('motorcycles', [MotorcycleController::class, 'store']);
    Route::patch('motorcycles/{motorcycle}', [MotorcycleController::class, 'update']);
    Route::delete('motorcycles/{motorcycle}', [MotorcycleController::class, 'destroy']);

    Route::post('/motorcycles/{motorcycle}/images', [MotorcycleImageController::class, 'store']);
    Route::delete('/images/{image}', [MotorcycleImageController::class, 'destroy']);

    Route::get('/me/favorites', [FavoriteController::class, 'index']);
    Route::post('/motorcycles/{motorcycle}/favorite', [FavoriteController::class, 'store']);
    Route::delete('/motorcycles/{motorcycle}/favorite', [FavoriteController::class, 'destroy']);
});
