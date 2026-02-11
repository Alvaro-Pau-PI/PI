<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProfileController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Gestión de perfil
    Route::put('/user/profile-information', [ProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);

    // Reseñas protegidas
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
});

// Rutas de productos (Públicas)
Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{product}', [ProductController::class, 'apiShow']);

// Rutas de reviews (Públicas)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
