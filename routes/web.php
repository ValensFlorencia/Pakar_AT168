<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\BasisPengetahuanCFController;
use App\Http\Controllers\BasisPengetahuanDSController;
use App\Http\Controllers\DiagnosaController;


/*
|--------------------------------------------------------------------------
| Web Routes (Clean Version)
|--------------------------------------------------------------------------
| Semua route publik diarahkan ke /login.
| Route lain diproteksi oleh middleware "auth".
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

    // =====================
    // DATA PENYAKIT (CRUD)
    // =====================
    Route::resource('penyakit', PenyakitController::class);

    // =====================
    // DATA GEJALA (CRUD)
    // =====================
    Route::resource('gejala', GejalaController::class);


    // ====================================
    // BASIS PENGETAHUAN CERTAINTY FACTOR
    // ====================================
    Route::resource('basis-cf', BasisPengetahuanCFController::class)->names([
    'index'   => 'basis_pengetahuan_cf.index',
    'create'  => 'basis_pengetahuan_cf.create',
    'store'   => 'basis_pengetahuan_cf.store',
    'edit'    => 'basis_pengetahuan_cf.edit',
    'update'  => 'basis_pengetahuan_cf.update',
    'destroy' => 'basis_pengetahuan_cf.destroy',
    ]);

    // ====================================
    // BASIS PENGETAHUAN DEMPSTER SHAFER
    // ====================================
    Route::resource('basis-ds', BasisPengetahuanDSController::class)->names([
    'index'   => 'basis_pengetahuan_ds.index',
    'create'  => 'basis_pengetahuan_ds.create',
    'store'   => 'basis_pengetahuan_ds.store',
    'edit'    => 'basis_pengetahuan_ds.edit',
    'update'  => 'basis_pengetahuan_ds.update',
    'destroy' => 'basis_pengetahuan_ds.destroy',
]);

    // =====================
    // PROFILE (opsional)
    // =====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index');
    Route::post('/diagnosa/hasil', [DiagnosaController::class, 'hasil'])->name('diagnosa.hasil');


// ROUTE LOGIN/LOGOUT ADA DI auth.php
require __DIR__ . '/auth.php';
