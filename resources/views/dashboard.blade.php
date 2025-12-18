@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

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

            <a href="{{ route('penyakit.index') }}" class="btn btn-outline">
                <i class="fas fa-bacteria"></i>
                Lihat Data Penyakit
            </a>
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
<style>

    /* HERO */
    .hero-card{
        max-width:1800px;
        margin:0 auto 18px auto;
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:26px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        display:flex;
        gap:22px;
        align-items:stretch;
        justify-content:space-between;
        flex-wrap:wrap;
    }

    .hero-left{ flex:1; min-width:320px; }
    .hero-right{
        width:360px;
        min-width:280px;
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .hero-badge{
        display:inline-flex;
        align-items:center;
        gap:10px;
        padding:8px 12px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#111827; /* ✅ jadi hitam */
        font-weight:900;
        font-size:12px;
        margin-bottom:10px;
    }
    .hero-emoji{ font-size:16px; }

    .hero-title{
        margin:0 0 10px 0;
        font-size:22px;
        font-weight:900;
        color:#111827; /* ✅ jadi hitam */
        letter-spacing:-0.2px;
    }

    .hero-text{
        margin:0 0 16px 0;
        color:#111827; /* ✅ jadi hitam */
        font-weight:600;
        line-height:1.65;
        font-size:14px;
        opacity:.95;
    }

    .hero-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .hero-mini{
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:12px 14px;
        display:flex;
        gap:12px;
        align-items:center;
    }
    .mini-icon{
        width:38px;height:38px;
        border-radius:12px;
        background:#fff9c4;
        border:1px solid #fde68a;
        display:flex;align-items:center;justify-content:center;
        font-size:18px;
        flex-shrink:0;
    }
    .mini-title{
        font-weight:900;
        color:#111827; /* ✅ jadi hitam */
        font-size:13px;
        margin-bottom:2px;
    }
    .mini-sub{
        color:#111827; /* ✅ jadi hitam */
        font-weight:600;
        font-size:12px;
        opacity:.85;
    }

    /* STATS */
    .stats-container{
        max-width:1800px;
        margin:0 auto 18px auto;
        display:grid;
        grid-template-columns:repeat(3, minmax(240px, 1fr));
        gap:14px;
    }

    .stat-card{
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:18px 18px;
        box-shadow:0 4px 16px rgba(0,0,0,0.05);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        transition:.18s ease;
    }
    .stat-card:hover{
        transform: translateY(-2px);
        box-shadow:0 8px 20px rgba(0,0,0,0.08);
        background:#fffef5;
    }

    .stat-title{
        font-size:13px;
        font-weight:900;
        color:#111827; /* ✅ jadi hitam */
        opacity:.9;
        margin-bottom:6px;
    }

    .stat-number{
        font-size:28px;
        font-weight:900;
        color:#111827; /* ✅ jadi hitam */
        letter-spacing:-0.4px;
        line-height:1;
    }

    .stat-icon{
        font-size:30px;
        opacity:.9;
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:14px;
        width:48px;height:48px;
        display:flex;align-items:center;justify-content:center;
        flex-shrink:0;
    }

    /* INFO GRID */
    .info-grid{
        max-width:1800px;
        margin:0 auto;
        display:grid;
        grid-template-columns:repeat(3, minmax(260px, 1fr));
        gap:14px;
    }

    .info-card{
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:18px 18px;
        box-shadow:0 4px 16px rgba(0,0,0,0.05);
        transition:.18s ease;
    }
    .info-card:hover{
        transform: translateY(-2px);
        box-shadow:0 8px 20px rgba(0,0,0,0.08);
        background:#fffef5;
    }

    .info-head{
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:10px;
    }

    .info-icon{
        width:40px;height:40px;
        border-radius:14px;
        background:#fff9c4;
        border:1px solid #fde68a;
        display:flex;align-items:center;justify-content:center;
        font-size:18px;
        flex-shrink:0;
    }

    .info-title{
        font-weight:900;
        color:#111827; /* ✅ jadi hitam */
        font-size:15px;
    }

    .info-text{
        margin:0;
        color:#111827; /* ✅ jadi hitam */
        font-weight:600;
        font-size:13px;
        line-height:1.65;
        opacity:.9;
    }

    /* Buttons (selaras) */
    .btn{
        padding:12px 18px;
        border:none;
        border-radius:12px;
        font-size:14px;
        font-weight:900;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:10px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-submit{
        background:#fcd34d;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }
    .btn-submit:hover{
        background:#fcd34d;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    .btn-outline{
        background:#ffffff;
        color:#111827; /* ✅ jadi hitam */
        border:2px solid #fde68a;
    }
    .btn-outline:hover{
        transform: translateY(-1px);
        border-color:#fff9c4;
    }

    @media (max-width:1024px){
        .stats-container{ grid-template-columns:1fr; }
        .info-grid{ grid-template-columns:1fr; }
        .hero-right{ width:100%; }
    }

    @media (max-width:768px){
        .hero-card{ padding:18px; }
        .hero-actions .btn{ width:100%; justify-content:center; }
    }
</style>
@endsection
