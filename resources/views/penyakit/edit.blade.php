<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penyakit - Sistem Pakar</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            min-height: 100vh;
        }

        .container { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #ffe66d 0%, #ffd93d 100%);
            color: #5a4a2a;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .logo {
            padding: 30px 20px;
            background: rgba(255,255,255,0.3);
            border-bottom: 2px solid rgba(255,255,255,0.4);
        }

        .logo h1 { font-size: 24px; font-weight: 700; }

        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover { background: rgba(255,255,255,0.4); }
        .menu-item.active { background: rgba(255,255,255,0.5); border-left-color:#f9a825; }

        /* Main */
        .main-content {
            flex: 1;
            background: linear-gradient(135deg, #fff9e6 0%, #ffe4b3 100%);
        }

        .header {
            background: white;
            padding: 25px 40px;
            border-bottom: 3px solid #ffe66d;
        }

        .header h2 { font-size:24px; color:#5a4a2a; }

        .content { padding:40px; }
        .page-title { font-size:32px; color:#5a4a2a; margin-bottom:30px; font-weight:700; }

        .form-container {
            background:white;
            padding:40px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.1);
        }

        .form-group { margin-bottom:25px; }
        .form-group label { font-weight:600; margin-bottom:10px; display:block; }

        .form-group input,
        .form-group textarea {
            width:100%;
            padding:15px;
            border:2px solid #ffe4b3;
            border-radius:12px;
            background:#fffdf7;
            font-size:15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            line-height:1.5;
        }

        textarea { min-height:120px; resize:vertical; }

        .input-hint { font-size:12px; color:#8b7355; margin-top:5px; }

        .button-group { display:flex; gap:15px; margin-top:30px; }

        .btn {
            padding:14px 32px;
            border:none;
            border-radius:25px;
            cursor:pointer;
            font-weight:600;
            font-size:16px;
        }

        .btn-primary {
            background:linear-gradient(135deg,#f6b93b 0%,#e58e26 100%);
            color:white;
        }

        .btn-secondary {
            background:#e0e0e0;
            color:#5a4a2a;
            text-decoration:none;
            padding:14px 32px;
            display:inline-block;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="sidebar">
        <div class="logo"><h1>🐔 Sistem Pakar</h1></div>

        <div class="menu">
            <a href="{{ route('dashboard') }}" class="menu-item"><span>📊</span><span>Dashboard</span></a>
            <a href="{{ route('penyakit.index') }}" class="menu-item active"><span>🦠</span><span>Data Penyakit</span></a>
        </div>
    </div>

    <div class="main-content">

        <div class="header"><h2>Edit Penyakit</h2></div>

        <div class="content">

            <h1 class="page-title">Form Edit Penyakit</h1>

            <div class="form-container">

                <form action="{{ route('penyakit.update', $penyakit->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Kode Penyakit</label>
                        <input value="{{ $penyakit->kode_penyakit }}" readonly style="background:#fff3c4; font-weight:600; cursor:not-allowed;">
                        <div class="input-hint">Kode tidak dapat diubah.</div>
                    </div>

                    <div class="form-group">
                        <label>Nama Penyakit</label>
                        <input name="nama_penyakit" value="{{ $penyakit->nama_penyakit }}" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" required>{{ $penyakit->deskripsi }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Solusi / Penanganan</label>
                        <textarea name="solusi" required>{{ $penyakit->solusi }}</textarea>
                    </div>

                    <div class="button-group">
                        <button class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('penyakit.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

</body>
</html>
