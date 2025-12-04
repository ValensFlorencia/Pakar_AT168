{{-- resources/views/penyakit/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyakit – Sistem Pakar Ayam</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR SAMA TEMA KUNING PASTEL */
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
            font-size: 22px;
            font-weight: 700;
        }

        .menu {
            padding: 20px 0;
        }

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
            font-weight: 600;
        }

        .menu-icon {
            font-size: 20px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            background: linear-gradient(135deg, #fff9e6 0%, #ffe4b3 100%);
        }

        .header {
            background: white;
            padding: 20px 35px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #ffe66d;
        }

        .header-title {
            color: #5a4a2a;
            font-size: 18px;
            font-weight: 600;
        }

        .logout-btn {
            background: linear-gradient(135deg, #f6b93b 0%, #e58e26 100%);
            color: white;
            border: none;
            padding: 8px 22px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(246,185,59,0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(246,185,59,0.4);
        }

        .content {
            padding: 35px;
        }

        .page-title {
            color: #5a4a2a;
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .page-subtitle {
            color: #8b7355;
            margin-bottom: 25px;
            font-size: 15px;
        }

        /* ALERT */
        .alert-success {
            background: #ecfdf3;
            border-left: 4px solid #16a34a;
            padding: 10px 15px;
            border-radius: 10px;
            color: #166534;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* CARD TABLE */
        .card {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px 25px 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-header h3 {
            font-size: 18px;
            color: #5a4a2a;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
            color: #5a4a2a;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(253, 160, 133, 0.5);
            text-decoration: none;
        }

        .btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background: #fff7c2;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #5a4a2a;
            font-weight: 600;
            border-bottom: 1px solid #f1e4b5;
        }

        tbody tr:nth-child(even) {
            background: #fffdf2;
        }

        tbody tr:hover {
            background: #fff7da;
        }

        td.actions {
            white-space: nowrap;
            text-align: center;
        }


        .btn-edit,
        .btn-delete {
            border: none;
            border-radius: 15px;
            padding: 5px 12px;
            font-size: 12px;
            cursor: pointer;
            font-weight: 600;
            margin-right: 5px;
        }

        .btn-edit {
            background: #fde68a;
            color: #854d0e;
        }

        .btn-edit:hover {
            background: #fcd34d;
        }

        .btn-delete {
            background: #fecaca;
            color: #b91c1c;
        }

        .btn-delete:hover {
            background: #fca5a5;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #8b7355;
            font-size: 13px;
            margin-top: 30px;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 80px;
            }
            .logo h1,
            .menu-item span {
                display: none;
            }
            .content {
                padding: 20px;
            }
            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">

    {{-- SIDEBAR --}}
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
                <span class="menu-icon">🦠</span>
                <span>Data Penyakit</span>
            </a>
            {{-- nanti bisa tambah menu Gejala, Rule, dsb di sini --}}
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <div class="header">
            <div class="header-title">Data Penyakit</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>
        </div>

        <div class="content">
            <h1 class="page-title">Data Penyakit Ayam Petelur</h1>
            <p class="page-subtitle">
                Kelola daftar penyakit yang digunakan pada proses diagnosa sistem pakar.
            </p>

            {{-- pesan sukses --}}
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3>Daftar Penyakit</h3>
                    <a href="{{ route('penyakit.create') }}" class="btn-primary">+ Tambah Penyakit</a>
                </div>

                <table>
                    <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 110px;">Kode</th>
                        <th style="width: 180px;">Nama Penyakit</th>
                        <th>Deskripsi</th>
                        <th>Solusi</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($penyakits as $index => $penyakit)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $penyakit->kode_penyakit }}</td>
                            <td>{{ $penyakit->nama_penyakit }}</td>
                            <td>{{ $penyakit->deskripsi }}</td>
                            <td>{{ $penyakit->solusi }}</td>
                            <td class="actions">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('penyakit.edit', $penyakit->id) }}">
                                    <button type="button" class="btn-edit">Edit</button>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('penyakit.destroy', $penyakit->id) }}"
                                    method="POST"
                                    style="display:inline"
                                    onsubmit="return confirm('Yakin ingin menghapus penyakit ini? Data tidak dapat dipulihkan!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:18px; color:#999;">
                                Belum ada data penyakit.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer">
                © {{ date('Y') }} Sistem Pakar Diagnosa Penyakit Ayam
            </div>
        </div>
    </div>
</div>
</body>
</html>
