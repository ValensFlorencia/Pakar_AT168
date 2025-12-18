<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pakar Ayam')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        /* ================= SIDEBAR - FIXED POSITION ================= */
        .sidebar {
            width: 260px;
            background: #fff9c4;
            color: #000000;
            padding: 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo {
            padding: 20px 22px;
            background: transparent;
            border-bottom: 2px solid rgba(255,255,255,0.45);
        }

        .logo{
            padding: 20px 22px;
            background: transparent;
            border-bottom: 2px solid rgba(255,255,255,0.45);

            /* ✅ bikin judul di tengah */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo h1{
            font-size: 18px;
            font-weight: 700;
            color: #000000;

            /* ✅ center */
            width: 100%;
            text-align: center;
            justify-content: center;
        }


        .sidebar-section {
            padding: 18px 0 6px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            opacity: 0.7;
            padding: 0 22px 6px;
            color: #000000;
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

        .menu-item i {
            width: 20px;
            text-align: center;
            color: #000000;
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

        /* ✅ SIDEBAR FOOTER UNTUK LOGOUT DI BAWAH */
        .sidebar-footer {
            margin-top: auto;
            padding: 18px 22px;
            border-top: 2px solid rgba(255,255,255,0.45);
        }

        .sidebar-logout {
            width: 100%;
            background: #fcd34d;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 18px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(180, 83, 9, 0.35);
            transition: 0.25s ease;
        }

        .sidebar-logout:hover {
            background: #92400e;
            transform: translateY(-1px);
        }

        /* ================= MAIN AREA - DENGAN MARGIN LEFT ================= */
        .main-content {
            flex: 1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            margin-left: 260px;
            width: calc(100% - 260px);
        }

        /* HEADER */
        .header {
            background: #ffffff;
            padding: 16px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 2px solid rgba(255,255,255,0.45);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        /* ✅ PROFILE DI HEADER */
        .profile-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-name {
            font-size: 14px;
            color: #5a4a2a;
            font-weight: 600;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fde68a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #854d0e;
        }

        /* ================= CONTENT ================= */
        .content {
            padding: 35px 40px 40px;
            flex: 1;
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
            border-radius: 17px;
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
            font-size: 15px;
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
            .sidebar {
                width: 80px;
            }
            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
            .logo h1 span.text {
                display: none;
            }
            .sidebar-title {
                display: none;
            }
            .menu-item span.label {
                display: none;
            }
            .content {
                padding: 24px;
            }
        }
    </style>
    <style>
  /* ===== GLOBAL TYPOGRAPHY (samain seperti Data Gejala) ===== */
  body{
    font-family:'Inter', -apple-system, BlinkMacSystemFont,'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    letter-spacing:-0.1px;
    color:#111827;
    background:#ffffff; /* optional kalau mau putih */
  }

  /* header halaman */
  .page-title{
    color:#111827;
    font-weight:600;     /* seperti Data Gejala (lebih “clean”) */
    letter-spacing:-0.2px;
  }

  .page-subtitle{
    color:#374151;
    font-weight:500;
    line-height:1.6;
  }

  /* teks umum biar ga “bold semua” */
  p, li, td, span, div{
    font-weight:400;
  }
</style>

</head>
<body>
<div class="container">

    {{-- SIDEBAR FIXED - TIDAK AKAN SCROLL --}}
    <div class="sidebar">
        <div class="logo">
            <h1>
                <span class="text">Sistem Pakar</span>
            </h1>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">MASTER</div>
            <a href="{{ route('dashboard') }}"
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="label">Dashboard</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">DATA</div>

            <a href="{{ route('gejala.index') }}"
               class="menu-item {{ request()->is('gejala*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope"></i>
                <span class="label">Data Gejala</span>
            </a>

            <a href="{{ route('penyakit.index') }}"
               class="menu-item {{ request()->is('penyakit*') ? 'active' : '' }}">
                <i class="fas fa-virus"></i>
                <span class="label">Data Penyakit</span>
            </a>

            <a href="{{ route('basis_pengetahuan_cf.index') }}"
               class="menu-item {{ request()->is('basis-cf*') ? 'active' : '' }}">
                <i class="fas fa-brain"></i>
                <span class="label">Basis Pengetahuan CF</span>
            </a>

            <a href="{{ route('basis_pengetahuan_ds.index') }}"
               class="menu-item {{ request()->is('basis-ds*') ? 'active' : '' }}">
                <i class="fas fa-network-wired"></i>
                <span class="label">Basis Pengetahuan DS</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">DIAGNOSA</div>

            <a href="{{ route('diagnosa.index') }}"
               class="menu-item {{ request()->is('diagnosa*') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>
                <span class="label">Diagnosa Penyakit</span>
            </a>

            <a href="{{ route('riwayat-diagnosa.index') }}"
               class="menu-item {{ request()->is('riwayat-diagnosa*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span class="label">Riwayat Diagnosa</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">ROLE</div>

            <a href="{{ route('users.index') }}"
               class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="label">Pengguna</span>
            </a>
        </div>

        {{-- ✅ LOGOUT PALING BAWAH SIDEBAR --}}
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- MAIN AREA - DENGAN MARGIN LEFT --}}
    <div class="main-content">

        {{-- ✅ HEADER SEKARANG PROFILE (Bukan Logout) --}}
        <div class="header">
            <div class="profile-box">
                <span class="profile-name">
                    <i class="fas fa-user-circle"></i> {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                </span>
                <div class="profile-avatar">
                    {{ strtoupper(substr(Auth::check() ? Auth::user()->name : 'G', 0, 1)) }}
                </div>
            </div>
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
