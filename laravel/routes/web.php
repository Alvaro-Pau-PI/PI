<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ProductController::class, 'index']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/productes', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/productes/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::view('/contacte', 'contact')->name('contact');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products/import', [ProductImportController::class, 'show'])->name('products.import.show');
    Route::post('/products/import', [ProductImportController::class, 'store'])->name('products.import.store');
});

require __DIR__.'/auth.php';
