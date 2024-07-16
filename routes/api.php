<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware('guest')->name('login');
    Route::post('register', 'register')->middleware('guest')->name('register');
    Route::get('refresh-token', 'refreshToken')->middleware('guest')->name('refresh-token');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
});
