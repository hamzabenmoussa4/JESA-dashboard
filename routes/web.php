<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.login');
// Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback']);
