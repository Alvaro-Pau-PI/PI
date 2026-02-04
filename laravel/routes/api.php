<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'apiIndex']);
Route::get('/products/{product}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);
Route::middleware('auth:sanctum')->post('/products/{product}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);
