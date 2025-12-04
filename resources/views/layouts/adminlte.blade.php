<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Sistem Pakar Ayam</title>

    {{-- AdminLTE + FontAwesome via CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">

    <style>
        /* background umum sama seperti login (kuning pastel) */
        body {
            background: linear-gradient(135deg, #fff7c7 0%, #ffe4b5 40%, #ffd19a 100%);
        }

        /* navbar putih dengan bayangan lembut */
        .main-header.navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(249, 168, 37, 0.25);
        }

        /* brand di sidebar */
        .main-sidebar {
            background: linear-gradient(180deg, #facc15, #fbbf24, #fb923c);
        }

        .brand-link {
            background: transparent !important;
            border-bottom: none !important;
        }

        .brand-link .brand-text {
            font-weight: 700;
            letter-spacing: .05em;
        }

        /* text sidebar */
        .sidebar,
        .sidebar a,
        .nav-sidebar > .nav-item > .nav-link {
            color: #fff !important;
        }

        .nav-sidebar > .nav-item > .nav-link.active {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #1f2933 !important;
            font-weight: 600;
        }

        .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(255, 255, 255, 0.18) !important;
        }

        /* wrapper konten transparan supaya gradient body keliatan */
        .content-wrapper {
            background: transparent;
        }

        /* card di dalam content dibuat putih dengan rounded & shadow lembut */
        .content-wrapper .card {
            border-radius: 1rem;
            box-shadow: 0 12px 30px rgba(0,0,0,0.05);
            border: none;
        }

        /* small-box pastel khusus sistem pakar ayam */
        .small-box.bg-ayam {
            background: linear-gradient(135deg, #22c55e, #a3e635);
            border-radius: 0.9rem;
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.35);
        }

        .small-box.bg-ayam .inner h3,
        .small-box.bg-ayam .inner p {
            color: #f9fafb;
        }

        .small-box.bg-ayam .icon {
            color: rgba(255, 255, 255, 0.75);
        }

        footer.main-footer {
            background: transparent;
            border-top: none;
            color: #6b7280;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Beranda</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <span class="brand-text text-white">Peduli Ayam</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    {{-- nanti tambah menu Gejala, Penyakit, Rule, Diagnosa di sini --}}
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content --}}
    <div class="content-wrapper">
        <section class="content pt-3 pb-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm text-center">
        &copy; {{ date('Y') }} Sistem Pakar Diagnosa Penyakit Ayam • Peduli Ayam
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
