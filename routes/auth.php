<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    // LOGIN FORM
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // LOGIN PROCESS
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});


Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
