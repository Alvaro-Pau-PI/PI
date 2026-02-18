<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Auth\SocialAuthController;

Route::get('/auth/google', [SocialAuthController::class, 'redirectToProvider'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleProviderCallback']);
