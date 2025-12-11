<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pakar Ayam')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffeaa7;
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: #ffd93d;
            color: #5a4a2a;
            padding: 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 20px 22px;
            background: transparent;
            border-bottom: 2px solid rgba(255,255,255,0.45);
        }

        .logo h1 {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a4a2a;
        }

        .logo h1 span {
            font-size: 20px;
        }

        .sidebar-section {
            padding: 18px 0 6px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 0.7;
            padding: 0 22px 6px;
            color: #5a4a2a;
        }

        .menu-item {
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border-left: 4px solid transparent;
            text-decoration: none;
            color: inherit;
            font-size: 14px;
            transition: 0.25s ease;
        }

        .menu-item span.icon {
            width: 20px;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.4);
            border-left-color: #e67e22;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.55);
            border-left-color: #e67e22;
            font-weight: 600;
        }

        /* ================= MAIN AREA ================= */
        .main-content {
            flex: 1;
            background: #fff8e1;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            background: #fff8e1 ;
            padding: 16px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 2px solid rgba(255,255,255,0.45);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .logout-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 6px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            transition: 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(231, 76, 60, 0.45);
            background: #c0392b;
        }

        /* ================= CONTENT ================= */
        .content {
            padding: 35px 40px 40px;
            flex: 1;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            color: #5a4a2a;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #8b7355;
            font-size: 15px;
            margin-bottom: 25px;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #8b7355;
            font-size: 13px;
        }

        /* ===== Dashboard cards & tabel bisa dipakai semua halaman ===== */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px,1fr));
            gap: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px 26px;
            box-shadow: 0 10px 28px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-info h2 {
            font-size: 42px;
            font-weight: 700;
        }

        .stat-info p {
            color: #7f8c8d;
            font-size: 15px;
        }

        .stat-icon {
            font-size: 40px;
            opacity: .35;
        }

        /* tabel di halaman penyakit */
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
            background: #3498db;
            border: none;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

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
            background: #fff9c4;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        tbody tr:nth-child(even) { background: #fffef5; }
        tbody tr:hover { background: #fff9c4; }

        td.actions {
            white-space: nowrap;
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
            transition: 0.2s ease;
        }

        .btn-edit:hover {
            background: #fcd34d;
        }

        .btn-delete {
            background: #fecaca;
            color: #b91c1c;
            transition: 0.2s ease;
        }

        .btn-delete:hover {
            background: #fca5a5;
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .logo h1 span.text { display: none; }
            .sidebar-title { display: none; }
            .menu-item span.label { display: none; }
            .content { padding: 24px; }
        }
    </style>
</head>
<body>
<div class="container">

    {{-- SIDEBAR SATU UNTUK SEMUA HALAMAN --}}
    <div class="sidebar">
        <div class="logo">
            <h1>
                <span>🐔</span>
                <span class="text">Sistem Pakar</span>
            </h1>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">MASTER</div>
            <a href="{{ route('dashboard') }}"
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                <span class="label">Dashboard</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">DATA</div>

            <a href="{{ route('gejala.index') }}"
               class="menu-item {{ request()->is('gejala*') ? 'active' : '' }}">
                <span class="icon">⚙️</span>
                <span class="label">Data Gejala</span>
            </a>

            <a href="{{ route('penyakit.index') }}"
               class="menu-item {{ request()->is('penyakit*') ? 'active' : '' }}">
                <span class="icon">🦠</span>
                <span class="label">Data Penyakit</span>
            </a>

            <a href="{{ route('basis_pengetahuan_cf.index') }}"
               class="menu-item {{ request()->is('basis-cf*') ? 'active' : '' }}">
                <span class="icon">🧠</span>
                <span class="label">Basis Pengetahuan CF</span>
            </a>

            <a href="{{ route('basis_pengetahuan_ds.index') }}"
               class="menu-item {{ request()->is('basis-ds*') ? 'active' : '' }}">
                <span class="icon">🧠</span>
                <span class="label">Basis Pengetahuan DS</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">DIAGNOSA</div>

            <a href="{{ route('diagnosa.index') }}"
               class="menu-item {{ request()->is('diagnosa*') ? 'active' : '' }}">
                <span class="icon">🩺</span>
                <span class="label">Diagnosa Penyakit</span>
            </a>
        </div>
    </div>

    {{-- MAIN AREA --}}
    <div class="main-content">
        <div class="header">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    🚪 Logout
                </button>
            </form>
        </div>

        <div class="content">
            @yield('content')
        </div>

        <div class="footer">
            © {{ date('Y') }} Sistem Pakar Diagnosa Penyakit Ayam
        </div>
    </div>

</div>
</body>
</html>
