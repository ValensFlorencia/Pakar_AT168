@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    .dashboard-header {
        margin-bottom: 35px;
    }

    .page-title {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 0;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 32px 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .stat-card.gejala {
        border-color: #3498db;
        background: linear-gradient(to bottom right, #ffffff, #ebf8ff);
    }

    .stat-card.gejala:hover {
        border-color: #2980b9;
    }

    .stat-card.penyakit {
        border-color: #e74c3c;
        background: linear-gradient(to bottom right, #ffffff, #fee);
    }

    .stat-card.penyakit:hover {
        border-color: #c0392b;
    }

    .stat-card.rule {
        border-color: #f39c12;
        background: linear-gradient(to bottom right, #ffffff, #fff8e1);
    }

    .stat-card.rule:hover {
        border-color: #e67e22;
    }

    .stat-info h2 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #2c3e50, #34495e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card.gejala .stat-info h2 {
        background: linear-gradient(135deg, #3498db, #2980b9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card.penyakit .stat-info h2 {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card.rule .stat-info h2 {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-info p {
        color: #7f8c8d;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .stat-icon {
        font-size: 56px;
        opacity: 0.2;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        opacity: 0.4;
        transform: scale(1.1) rotate(5deg);
    }

    .welcome-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border-left: 6px solid #3498db;
    }

    .welcome-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .welcome-card p {
        color: #7f8c8d;
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid;
    }

    .btn-primary-action {
        background: #3498db;
        color: #ffffff;
        border-color: #3498db;
    }

    .btn-primary-action:hover {
        background: #2980b9;
        border-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
    }

    .btn-secondary-action {
        background: #ffffff;
        color: #3498db;
        border-color: #3498db;
    }

    .btn-secondary-action:hover {
        background: #3498db;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.2);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        border-left: 4px solid;
    }

    .info-card.blue {
        border-left-color: #3498db;
    }

    .info-card.red {
        border-left-color: #e74c3c;
    }

    .info-card.orange {
        border-left-color: #f39c12;
    }

    .info-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card p {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 28px;
        }

        .stat-info h2 {
            font-size: 36px;
        }
    }
</style>

<div class="dashboard-header">
    <h1 class="page-title">
        <span>👋</span>
        Selamat Datang di Dashboard
    </h1>
    <p class="page-subtitle">Sistem Pakar Diagnosa Penyakit Ayam</p>
</div>

<div class="welcome-card">
    <h3>
        <span>🐔</span>
        Sistem Pakar Diagnosa Penyakit Ayam
    </h3>
    <p>
        Sistem ini dirancang untuk membantu Anda mendiagnosa penyakit ayam dengan metode Certainty Factor (CF) dan Dempster-Shafer (DS). Pilih gejala yang dialami ayam dan sistem akan memberikan hasil diagnosa dengan tingkat kepercayaan tertentu.
    </p>
    <div class="action-buttons">
        <a href="{{ route('diagnosa.index') }}" class="btn-action btn-primary-action">
            <span>🩺</span>
            Mulai Diagnosa
        </a>
        <a href="{{ route('penyakit.index') }}" class="btn-action btn-secondary-action">
            <span>🦠</span>
            Lihat Data Penyakit
        </a>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card gejala">
        <div class="stat-info">
            <h2>{{ $totalGejala ?? 0 }}</h2>
            <p>Total Gejala</p>
        </div>
        <div class="stat-icon">⚙️</div>
    </div>

    <div class="stat-card penyakit">
        <div class="stat-info">
            <h2>{{ $totalPenyakit ?? 0 }}</h2>
            <p>Total Penyakit</p>
        </div>
        <div class="stat-icon">🦠</div>
    </div>

    <div class="stat-card rule">
        <div class="stat-info">
            <h2>{{ $totalRule ?? 0 }}</h2>
            <p>Total Rule</p>
        </div>
        <div class="stat-icon">📋</div>
    </div>
</div>

<div class="info-grid">
    <div class="info-card blue">
        <h4>
            <span>🔍</span>
            Data Gejala
        </h4>
        <p>
            Kelola data gejala penyakit ayam yang akan digunakan untuk proses diagnosa. Setiap gejala memiliki kode unik untuk identifikasi.
        </p>
    </div>

    <div class="info-card red">
        <h4>
            <span>🦠</span>
            Data Penyakit
        </h4>
        <p>
            Database lengkap penyakit ayam beserta solusi penanganannya. Informasi penting untuk rujukan hasil diagnosa.
        </p>
    </div>

    <div class="info-card orange">
        <h4>
            <span>🧠</span>
            Basis Pengetahuan
        </h4>
        <p>
            Aturan relasi antara gejala dan penyakit dengan nilai CF/DS. Basis pengetahuan yang digunakan sistem untuk mendiagnosa.
        </p>
    </div>
</div>

@endsection
