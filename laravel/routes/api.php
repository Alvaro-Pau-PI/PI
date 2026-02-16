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
// IMPORTANTE: Rutas específicas ANTES que rutas con parámetros
Route::get('/products/featured', [ProductController::class, 'featured']); // Productos destacados (IA)
Route::get('/products/sustainable', [ProductController::class, 'sustainable']); // Productos sostenibles 🌱
Route::get('/products/sustainability-stats', [ProductController::class, 'sustainabilityStats']); // Estadísticas ASG
Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{product}', [ProductController::class, 'apiShow']);
Route::get('/products/{id}/related', [ProductController::class, 'related']); // Productos relacionados (IA)

// Rutas de reviews (Públicas)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
