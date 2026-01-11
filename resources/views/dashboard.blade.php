@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = Auth::user();
    $role = $user->role ?? 'peternak';

    // statistik peternak (opsional dari controller)
    $totalRiwayatSaya   = $totalRiwayatSaya   ?? 0;
    $diagnosaBulanIni   = $diagnosaBulanIni   ?? 0;
    $lastDiagnosaAt     = $lastDiagnosaAt     ?? null; // Carbon / string
@endphp

<div class="page-header" style="margin-bottom:26px;">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Sistem Pakar Diagnosa Penyakit Ayam (CF & DS)</p>
</div>

<div class="hero-card">
    <div class="hero-left">
        <div class="hero-badge">
            <span class="hero-emoji">🐔</span>
            <span>Sistem Pakar</span>
        </div>

        <h2 class="hero-title">Selamat datang 👋</h2>
        <p class="hero-text">
            Sistem ini membantu mendiagnosa penyakit ayam menggunakan metode <b>Certainty Factor (CF)</b> dan
            <b>Dempster-Shafer (DS)</b> berdasarkan gejala yang dipilih.
        </p>

        <div class="hero-actions">
            <a href="{{ route('diagnosa.index') }}" class="btn btn-submit">
                <i class="fas fa-stethoscope"></i>
                Mulai Diagnosa
            </a>

            @if($role === 'peternak')
                <a href="{{ route('riwayat-diagnosa.index') }}" class="btn btn-outline">
                    <i class="fas fa-clock-rotate-left"></i>
                    Riwayat Diagnosa
                </a>
            @else
                <a href="{{ route('penyakit.index') }}" class="btn btn-outline">
                    <i class="fas fa-bacteria"></i>
                    Lihat Data Penyakit
                </a>
            @endif
        </div>
    </div>

    <div class="hero-right">
        <div class="hero-mini">
            <div class="mini-icon">🩺</div>
            <div class="mini-text">
                <div class="mini-title">Cepat</div>
                <div class="mini-sub">Pilih gejala & bobot CF</div>
            </div>
        </div>

        <div class="hero-mini">
            <div class="mini-icon">🧠</div>
            <div class="mini-text">
                <div class="mini-title">Akurat</div>
                <div class="mini-sub">CF & DS untuk tingkat keyakinan</div>
            </div>
        </div>

        <div class="hero-mini">
            <div class="mini-icon">📋</div>
            <div class="mini-text">
                <div class="mini-title">Rapi</div>
                <div class="mini-sub">Basis pengetahuan terstruktur</div>
            </div>
        </div>
    </div>
</div>

{{-- STATS: beda untuk peternak vs pakar/pemilik --}}
@if($role === 'peternak')
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Riwayat Diagnosa Saya</div>
                <div class="stat-number">{{ $totalRiwayatSaya }}</div>
            </div>
            <div class="stat-icon">📒</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Diagnosa Bulan Ini</div>
                <div class="stat-number">{{ $diagnosaBulanIni }}</div>
            </div>
            <div class="stat-icon">🗓️</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Terakhir Diagnosa</div>
                <div class="stat-number" style="font-size:14px; line-height:1.3;">
                    @if($lastDiagnosaAt)
                        {{ \Carbon\Carbon::parse($lastDiagnosaAt)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="stat-icon">⏱️</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🩺</div>
                <div class="info-title">Mulai Diagnosa</div>
            </div>
            <p class="info-text">
                Pilih gejala yang terlihat pada ayam, tentukan bobot keyakinan (CF), lalu sistem akan menampilkan hasil peringkat CF & DS.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🧾</div>
                <div class="info-title">Riwayat Diagnosa</div>
            </div>
            <p class="info-text">
                Semua hasil diagnosa yang pernah kamu lakukan tersimpan otomatis (tanggal, gejala yang dipilih, dan ranking penyakit).
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">✅</div>
                <div class="info-title">Saran Penanganan</div>
            </div>
            <p class="info-text">
                Gunakan saran penanganan awal sebagai panduan cepat. Untuk kasus berat/menular, tetap konfirmasi ke dokter hewan/pakar.
            </p>
        </div>
    </div>
@else
    {{-- tampilan lama untuk pakar/pemilik --}}
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Gejala</div>
                <div class="stat-number">{{ $totalGejala ?? 0 }}</div>
            </div>
            <div class="stat-icon">⚙️</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Penyakit</div>
                <div class="stat-number">{{ $totalPenyakit ?? 0 }}</div>
            </div>
            <div class="stat-icon">🦠</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Rule</div>
                <div class="stat-number">{{ $totalRule ?? 0 }}</div>
            </div>
            <div class="stat-icon">📌</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🔍</div>
                <div class="info-title">Data Gejala</div>
            </div>
            <p class="info-text">
                Kelola daftar gejala penyakit ayam untuk proses diagnosa. Setiap gejala memiliki kode unik untuk identifikasi.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🦠</div>
                <div class="info-title">Data Penyakit</div>
            </div>
            <p class="info-text">
                Database penyakit ayam beserta solusi penanganannya. Menjadi rujukan utama pada hasil diagnosa.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🧠</div>
                <div class="info-title">Basis Pengetahuan</div>
            </div>
            <p class="info-text">
                Aturan relasi gejala–penyakit dengan bobot CF/DS sebagai inti penalaran sistem dalam memberikan diagnosa.
            </p>
        </div>
    </div>
@endif
@endsection
