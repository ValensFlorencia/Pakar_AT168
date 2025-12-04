<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosa Penyakit Ayam - Dashboard</title>

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

        /* SIDEBAR */
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
            border-left: 4px solid transparent;
            text-decoration: none;
            color: inherit;
            transition: 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.4);
            border-left-color: #f9a825;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.5);
            border-left-color: #f9a825;
        }

        /* MAIN AREA */
        .main-content {
            flex: 1;
            background: linear-gradient(135deg, #fff9e6 0%, #ffe4b3 100%);
        }

        /* HEADER — SUDAH DISAMAKAN WARNA */
        .header {
            background: linear-gradient(180deg, #ffe66d 0%, #ffd93d 100%);
            padding: 25px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 3px solid #e6c600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .logout-btn {
            background: linear-gradient(135deg, #f6b93b 0%, #e58e26 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 4px 15px rgba(246,185,59,0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
        }

        /* CONTENT */
        .content {
            padding: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: #5a4a2a;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #8b7355;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 35px;
        }

        /* STAT CARDS */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
            gap: 25px;
        }

        .stat-card {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
        }

        .stat-info h2 {
            font-size: 48px;
            font-weight: 700;
        }

        .stat-info p {
            color: #7f8c8d;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #8b7355;
            margin-top: 40px;
        }
    </style>

</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
            <h1>🐔 Sistem Pakar</h1>
        </div>

        <div class="menu">
            <a href="{{ route('dashboard') }}"
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>📊</span> <span>Dashboard</span>
            </a>

            <a href="{{ route('penyakit.index') }}"
               class="menu-item {{ request()->is('penyakit*') ? 'active' : '' }}">
                <span>☠️</span> <span>Data Penyakit</span>
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- HEADER -->
        <div class="header">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn">🚪 Logout</button>
            </form>
        </div>

        <div class="content">
            <h1 class="page-title">Beranda</h1>
            <p class="page-subtitle">Ringkasan Sistem Pakar Diagnosa Penyakit Ayam</p>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-info">
                        <h2>{{ $totalGejala ?? 0 }}</h2>
                        <p>Total Gejala</p>
                    </div>
                    <div class="stat-icon">⚙️</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h2>{{ $totalPenyakit ?? 0 }}</h2>
                        <p>Total Penyakit</p>
                    </div>
                    <div class="stat-icon">☠️</div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h2>{{ $totalRule ?? 0 }}</h2>
                        <p>Total Rule</p>
                    </div>
                    <div class="stat-icon">📋</div>
                </div>
            </div>

            <div class="footer">
                © {{ date('Y') }} Sistem Pakar Diagnosa Penyakit Ayam
            </div>
        </div>

    </div>

</div>

</body>
</html>
