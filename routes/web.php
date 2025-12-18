<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\BasisPengetahuanCFController;
use App\Http\Controllers\BasisPengetahuanDSController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\RiwayatDiagnosaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {

    // =====================
    // DASHBOARD (SEMUA ROLE)
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // DIAGNOSA (PAKAR, PETERNAK, PEMILIK)
    // =====================
    Route::middleware(['role:pakar,peternak,pemilik'])->group(function () {
        Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index');
        Route::post('/diagnosa/proses', [DiagnosaController::class, 'proses'])->name('diagnosa.proses');
        Route::post('/diagnosa/hasil', [DiagnosaController::class, 'hasil'])->name('diagnosa.hasil');

        Route::get('/riwayat-diagnosa', [RiwayatDiagnosaController::class, 'index'])->name('riwayat-diagnosa.index');
        Route::get('/riwayat-diagnosa/{riwayat}', [RiwayatDiagnosaController::class, 'show'])->name('riwayat-diagnosa.show');
    });

    // =========================================================
    // MASTER DATA - VIEW (PAKAR, PETERNAK, PEMILIK) => BISA LIHAT
    // =========================================================
    Route::middleware(['role:pakar,peternak,pemilik'])->group(function () {
        // penyakit & gejala (view)
        Route::get('penyakit', [PenyakitController::class, 'index'])->name('penyakit.index');
        Route::get('gejala', [GejalaController::class, 'index'])->name('gejala.index');

        // jika butuh halaman detail (opsional)
        // Route::get('penyakit/{penyakit}', [PenyakitController::class, 'show'])->name('penyakit.show');
        // Route::get('gejala/{gejala}', [GejalaController::class, 'show'])->name('gejala.show');
    });

    // =========================================================
    // BASIS CF & DS - VIEW (PAKAR + PEMILIK) => BISA LIHAT
    // =========================================================
    Route::middleware(['role:pakar,pemilik'])->group(function () {

        // CF view
        Route::get('basis-cf', [BasisPengetahuanCFController::class, 'index'])
            ->name('basis_pengetahuan_cf.index');

        Route::get('/basis-cf/penyakit/{penyakit}', [BasisPengetahuanCFController::class, 'detailPenyakitCF'])
            ->name('basis_pengetahuan_cf.detail_penyakit');

        // DS view
        Route::get('basis-ds', [BasisPengetahuanDSController::class, 'index'])
            ->name('basis_pengetahuan_ds.index');

        Route::get('/basis-ds/penyakit/{penyakit}', [BasisPengetahuanDSController::class, 'detailPenyakitDS'])
            ->name('basis_pengetahuan_ds.detail_penyakit');
    });

    // =========================================================
    // MASTER DATA + BASIS CF/DS - CRUD (KHUSUS PAKAR)
    // =========================================================
    Route::middleware(['role:pakar'])->group(function () {

        // CRUD penyakit & gejala (tanpa index/show karena view sudah dibuka untuk semua)
        Route::resource('penyakit', PenyakitController::class)->except(['index', 'show']);
        Route::resource('gejala', GejalaController::class)->except(['index', 'show']);

        // CF CRUD khusus pakar (tanpa index/show)
        Route::resource('basis-cf', BasisPengetahuanCFController::class)
            ->except(['index', 'show'])
            ->names([
                'create'  => 'basis_pengetahuan_cf.create',
                'store'   => 'basis_pengetahuan_cf.store',
                'edit'    => 'basis_pengetahuan_cf.edit',
                'update'  => 'basis_pengetahuan_cf.update',
                'destroy' => 'basis_pengetahuan_cf.destroy',
            ]);

        // DS CRUD khusus pakar (tanpa index/show)
        Route::resource('basis-ds', BasisPengetahuanDSController::class)
            ->parameters(['basis-ds' => 'basis_ds'])
            ->except(['index', 'show'])
            ->names([
                'create'  => 'basis_pengetahuan_ds.create',
                'store'   => 'basis_pengetahuan_ds.store',
                'edit'    => 'basis_pengetahuan_ds.edit',
                'update'  => 'basis_pengetahuan_ds.update',
                'destroy' => 'basis_pengetahuan_ds.destroy',
            ]);
    });

    // =====================
    // KELOLA PENGGUNA (KHUSUS PEMILIK)
    // =====================
    Route::middleware(['role:pemilik'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // =====================
    // PROFILE (SEMUA ROLE)
    // =====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
