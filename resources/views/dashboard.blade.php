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
        }

        .menu-icon {
            font-size: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: linear-gradient(135deg, #fff9e6 0%, #ffe4b3 100%);
        }

        .header {
            background: white;
            padding: 25px 40px;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-title::before {
            content: '🏠';
            font-size: 22px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #f6b93b 0%, #e58e26 100%);
            color: white;
            border: none;
            padding: 10px 25px;
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
            padding: 40px;
        }

        .page-title {
            color: #5a4a2a;
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }

        .page-subtitle {
            color: #8b7355;
            margin-bottom: 35px;
            font-size: 16px;
            font-weight: 500;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stat-card.green::before {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.orange::before {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.yellow::before {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
        }

        .stat-info h2 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-card.green .stat-info h2 {
            color: #11998e;
        }

        .stat-card.orange .stat-info h2 {
            color: #f5576c;
        }

        .stat-card.yellow .stat-info h2 {
            color: #19547b;
        }

        .stat-info p {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: 500;
        }

        .stat-icon {
            font-size: 70px;
            opacity: 0.2;
        }

        .stat-card.green .stat-icon {
            color: #11998e;
        }

        .stat-card.orange .stat-icon {
            color: #f5576c;
        }

        .stat-card.yellow .stat-icon {
            color: #19547b;
        }

        .footer {
            text-align: center;
            padding: 30px;
            color: #8b7355;
            font-size: 14px;
            margin-top: 50px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .logo h1, .menu-item span:not(.menu-icon) {
                display: none;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>🐔 Sistem Pakar</h1>
            </div>
            <div class="menu">
                <a href="{{ route('dashboard') }}"
                   class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="menu-icon">📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('penyakit.index') }}"
                   class="menu-item {{ request()->is('penyakit*') ? 'active' : '' }}">
                    <span class="menu-icon">☠️</span>
                    <span>Data Penyakit</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="header-title">Beranda</div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">🚪 Logout</button>
                </form>
            </div>

            <div class="content">
                <h1 class="page-title">Beranda</h1>
                <p class="page-subtitle">Ringkasan Sistem Pakar Diagnosa Penyakit Ayam</p>

                <div class="stats-container">
                    <!-- Total Gejala -->
                    <div class="stat-card green">
                        <div class="stat-info">
                            <h2>{{ $totalGejala ?? 0 }}</h2>
                            <p>Total Gejala</p>
                        </div>
                        <div class="stat-icon">⚙️</div>
                    </div>

                    <!-- Total Penyakit -->
                    <div class="stat-card orange">
                        <div class="stat-info">
                            <h2>{{ $totalPenyakit ?? 0 }}</h2>
                            <p>Total Penyakit</p>
                        </div>
                        <div class="stat-icon">☠️</div>
                    </div>

                    <!-- Total Rule -->
                    <div class="stat-card yellow">
                        <div class="stat-info">
                            <h2>{{ $totalRule ?? 0 }}</h2>
                            <p>Total Rule</p>
                        </div>
                        <div class="stat-icon">📋</div>
                    </div>
                </div>

                <div class="footer">
                    © {{ date('Y') }} Sistem Pakar Diagnosa Penyakit Ayam •
                </div>
            </div>
        </div>
    </div>
</body>
</html>
