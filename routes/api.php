<?php


use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware('guest')->name('login');
    Route::get('refresh-token', 'refreshToken')->middleware('guest')->name('refresh-token');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});
