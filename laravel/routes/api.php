<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OrderController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Gestión de perfil
    Route::put('/user/profile-information', [ProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);

    // Reseñas protegidas
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Gestión de productos (Admin)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::get('/products/export', [\App\Http\Controllers\ProductImportController::class, 'exportAPI']);
    Route::post('/products/import', [\App\Http\Controllers\ProductImportController::class, 'storeAPI']);

    // Pedidos del usuario
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Gestión de pedidos (Admin)
    Route::get('/admin/orders', [OrderController::class, 'adminIndex']);
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
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

