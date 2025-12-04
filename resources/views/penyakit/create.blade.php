{{-- resources/views/penyakit/create.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penyakit - Sistem Pakar</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            min-height: 100vh;
        }

        .container { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #ffe66d 0%, #ffd93d 100%);
            color: #5a4a2a;
            padding: 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .logo {
            padding: 30px 20px;
            background: rgba(255,255,255,0.3);
            border-bottom: 2px solid rgba(255,255,255,0.4);
        }

        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .menu { padding: 20px 0; }

        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.4);
            border-left-color: #f9a825;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.5);
            border-left-color: #f9a825;
        }

        .menu-icon { font-size: 20px; }

        /* Main Content */
        .main-content {
            flex: 1;
            background: linear-gradient(135deg, #fff9e6 0%, #ffe4b3 100%);
            display: flex;
            flex-direction: column;
        }

        .header {
            background: white;
            padding: 25px 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-bottom: 3px solid #ffe66d;
        }

        .header h2 {
            color: #5a4a2a;
            font-size: 24px;
            font-weight: 700;
        }

        .content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .page-title {
            color: #5a4a2a;
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
        }

        .form-group { margin-bottom: 25px; }

        .form-group label {
            display: block;
            color: #5a4a2a;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #ffe4b3;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
            background: #fffdf7;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ffd93d;
            background: white;
            box-shadow: 0 0 0 3px rgba(255,217,61,0.1);
        }

        .form-group textarea { resize: vertical; min-height: 120px; }

        .input-hint {
            color: #8b7355;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }

        .error-text {
            color: #e11d48;
            font-size: 13px;
            margin-top: 6px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        .btn {
            padding: 14px 35px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f6b93b 0%, #e58e26 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(246,185,59,0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(246,185,59,0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #e0e0e0 0%, #c0c0c0 100%);
            color: #5a4a2a;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            line-height: 1.2;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .logo h1, .menu-item span { display: none; }
            .content { padding: 20px; }
            .form-container { padding: 25px; }
            .button-group { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="logo">
            <h1>🐔 Sistem Pakar</h1>
        </div>
        <div class="menu">
            <a href="{{ route('dashboard') }}" class="menu-item">
                <span class="menu-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('penyakit.index') }}" class="menu-item active">
                <span class="menu-icon">🧬</span>
                <span>Data Penyakit</span>
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="header">
            <h2>Tambah Penyakit</h2>
        </div>

        <div class="content">
            <h1 class="page-title">Form Tambah Penyakit</h1>

            <div class="form-container">
                {{-- ALERT VALIDASI GLOBAL (opsional) --}}
                @if ($errors->any())
                    <div style="
                        background:#fef2f2;
                        border:1px solid #fecaca;
                        color:#b91c1c;
                        padding:12px 16px;
                        border-radius:10px;
                        margin-bottom:20px;
                        font-size:14px;">
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin-left:18px; margin-top:6px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('penyakit.store') }}" method="POST">
                    @csrf

                    {{-- Kode Penyakit (AUTO) --}}
                    <div class="form-group">
                        <label for="kodePenyakit">Kode Penyakit</label>
                        <input
                            type="text"
                            id="kodePenyakit"
                            name="kode_penyakit"
                            value="{{ $newCode }}"
                            readonly
                            style="background:#fff3c4; cursor:not-allowed; font-weight:600;"
                        >
                        <div class="input-hint">Kode dibuat otomatis oleh sistem (P01, P02, dst).</div>
                        @error('kode_penyakit')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Penyakit --}}
                    <div class="form-group">
                        <label for="namaPenyakit">Nama Penyakit</label>
                        <input
                            type="text"
                            id="namaPenyakit"
                            name="nama_penyakit"
                            value="{{ old('nama_penyakit') }}"
                            placeholder="Contoh: Newcastle Disease (Tetelo / ND)"
                            required
                        >
                        <div class="input-hint">Masukkan nama penyakit pada ayam.</div>
                        @error('nama_penyakit')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            placeholder="Masukkan deskripsi penyakit, gejala khas, dan dampaknya."
                            required
                        >{{ old('deskripsi') }}</textarea>
                        <div class="input-hint">Jelaskan gejala dan karakteristik penyakit.</div>
                        @error('deskripsi')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Solusi / Penanganan --}}
                    <div class="form-group">
                        <label for="solusi">Solusi / Penanganan</label>
                        <textarea
                            id="solusi"
                            name="solusi"
                            placeholder="Masukkan solusi / penanganan (vaksin, obat, biosecurity, dll)."
                            required
                        >{{ old('solusi') }}</textarea>
                        <div class="input-hint">Jelaskan langkah penanganan dan pencegahan penyakit.</div>
                        @error('solusi')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('penyakit.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
