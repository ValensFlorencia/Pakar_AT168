<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyakitController;

/*
|--------------------------------------------------------------------------
| Web Routes (Clean Version)
|--------------------------------------------------------------------------
| Semua route publik diarahkan ke /login. Route lain di-protect middleware "auth".
*/

Route::redirect('/', '/login');

// =====================
// ROUTE PROTEKSI LOGIN
// =====================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // DATA PENYAKIT (CRUD)
    Route::resource('penyakit', PenyakitController::class);

    // PROFILE (opsional)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROUTE LOGIN/LOGOUT ADA DI auth.php
require __DIR__ . '/auth.php';
