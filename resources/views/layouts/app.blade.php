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
            background: #000000;
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
            min-height: 100vh;
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
            color: #000000;
        }

        /* ================= CONTENT ================= */
        .content {
            padding: 35px 40px 40px;
            flex: 1;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #000000;
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
            color: #000000;
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
            color: #000000;
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
            font-weight:600;
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
    /* ALERT (umum) */
    .alert{
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .alert-icon{
        font-size: 15px;
        line-height: 1;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .alert-content{ flex: 1; }
    .alert-content strong{ font-weight: 500; }

    /* SUKSES */
    .alert-success{
        background:#ecfdf5;
        border:1px solid #86efac;
        color:#065f46;
    }

    /* ERROR */
    .alert-error{
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#991b1b;
    }

    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
        margin:0 auto;
    }

    .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
        flex-wrap:wrap;
    }

    .card-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#111827;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .card-title i{ color:#f59e0b; }

    .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#6b7280;
        font-weight:600;
        opacity:1;
    }
    .card-subtitle i{ font-size:12px; }

    /* Table */
    .table-wrap{
        overflow-x:auto;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#ffffff;
    }

    .table{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        background:#fff;
    }

    .table thead{ background:#fffbeb; }

    .table th{
        padding:14px 14px;
        text-align:left;
        vertical-align:middle;
        border-bottom:1px solid #fde68a;
        color:#374151;
        font-weight:800;
        white-space:nowrap;
    }

    .table td{
        padding:14px 14px;
        text-align:left;
        vertical-align:middle;
        border-bottom:1px solid #fde68a;
        color:#111827;
        font-weight:500;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff7ed; }

    .action-group{
        display:flex;
        gap:10px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .empty{
        text-align:center;
        padding:22px;
        color:#6b7280;
        font-weight:700;
        background:#fffef5;
    }

    /* Buttons */
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
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }
    .btn-submit:hover{
        background:#d97706;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    .btn-mini{
        padding:8px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:6px;
        border:none;
        transition:.2s ease;
        white-space:nowrap;
    }

    .btn-edit{
        background:#f59e0b;
        color:#fff;
        box-shadow:0 4px 12px rgba(245,158,11,.22);
    }
    .btn-edit:hover{
        background:#d97706;
        transform:translateY(-1px);
    }

    .btn-delete{
        background:#fee2e2;
        color:#991b1b;
        border:1px solid #fecaca;
    }
    .btn-delete:hover{
        background:#fecaca;
        transform:translateY(-1px);
    }

    /* ✅ disabled state */
    .btn-disabled{
        opacity:.75;
        cursor:pointer; /* ✅ DIUBAH: dari not-allowed -> pointer (biar tidak ada tanda bulat merah) */
    }
    .btn-disabled:hover{
        transform:none;
        background:#fee2e2;
    }

    /* ===================== MODAL ===================== */
    .modal-overlay{
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
    }

    .modal-box{
        background: #fff;
        padding: 22px 24px;
        border-radius: 16px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,.22);
        text-align: center;
        border: 1px solid #fde68a;
        animation: modalPop .14s ease-out;
    }

    @keyframes modalPop {
        from { transform: scale(.97); opacity: .6; }
        to   { transform: scale(1); opacity: 1; }
    }

    .modal-box h3{
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }

    .modal-box p{
        margin: 0;
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
    }

    .modal-actions{
        margin-top: 18px;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-cancel{
        background:#f3f4f6;
        color:#111827;
    }
    .btn-cancel:hover{
        background:#e5e7eb;
        transform: translateY(-1px);
    }

    .btn-danger{
        background:#dc2626;
        color:#fff;
        box-shadow:0 6px 18px rgba(220,38,38,.18);
    }
    .btn-danger:hover{
        background:#b91c1c;
        transform: translateY(-1px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
        .modal-box{ max-width: 520px; }
    }
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
        color:#111827;
        font-weight:900;
        font-size:12px;
        margin-bottom:10px;
    }
    .hero-emoji{ font-size:16px; }

    .hero-title{
        margin:0 0 10px 0;
        font-size:22px;
        font-weight:900;
        color:#111827;
        letter-spacing:-0.2px;
    }

    .hero-text{
        margin:0 0 16px 0;
        color:#111827;
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
        color:#111827;
        font-size:13px;
        margin-bottom:2px;
    }
    .mini-sub{
        color:#111827;
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
        color:#111827;
        opacity:.9;
        margin-bottom:6px;
    }

    .stat-number{
        font-size:28px;
        font-weight:900;
        color:#111827;
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
        color:#111827;
        font-size:15px;
    }

    .info-text{
        margin:0;
        color:#111827;
        font-weight:600;
        font-size:13px;
        line-height:1.65;
        opacity:.9;
    }

    /* Buttons */
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
        color:#111827;
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
    /* ===========================
   SCOPED CSS (ANTI NULAR)
   Semua diprefix .user-edit-page
   =========================== */

    .user-edit-page .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        margin:0;
    }

    .user-edit-page .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
        flex-wrap:wrap;
    }

    .user-edit-page .card-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#111827;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .user-edit-page .card-title i{ color:#f59e0b; }

    .user-edit-page .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#6b7280;
        font-weight:600;
    }
    .user-edit-page .card-subtitle i{ font-size:12px; }

    .user-edit-page .grid-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .user-edit-page .label{
        display:block;
        font-size:13px;
        font-weight:600;
        color:#6b7280;
        margin-bottom:8px;
    }

    .user-edit-page .input,
    .user-edit-page .select{
        width:100%;
        padding:12px 14px;
        border:1.8px solid #fde68a;
        border-radius:14px;
        background:#fff;
        color:#111827;
        font-weight:600;
        outline:none;
        transition:.15s ease;
        font-size:14px;
    }

    .user-edit-page .input::placeholder{
        color:#9ca3af;
        font-weight:500;
    }

    .user-edit-page .input:focus,
    .user-edit-page .select:focus{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    /* select arrow */
    .user-edit-page .select{
        cursor:pointer;
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance:none;

        background:
            linear-gradient(45deg, transparent 50%, #9ca3af 50%),
            linear-gradient(135deg, #9ca3af 50%, transparent 50%),
            linear-gradient(to right, #fff, #fff);
        background-position:
            calc(100% - 20px) calc(1em + 2px),
            calc(100% - 15px) calc(1em + 2px),
            0 0;
        background-size:5px 5px,5px 5px,100% 100%;
        background-repeat:no-repeat;
        padding-right:44px;
    }

    /* placeholder select (abu & tidak bold) */
    .user-edit-page .select option.placeholder{
        color:#9ca3af;
        font-weight:500;
    }
    .user-edit-page .select:has(option:checked.placeholder){
        color:#9ca3af;
        font-weight:500;
    }

    .user-edit-page .help-text{
        margin-top:8px;
        font-size:12px;
        color:#6b7280;
        font-weight:600;
    }

    /* buttons */
    .user-edit-page .btn-actions{
        display:flex;
        gap:10px;
        margin-top:18px;
        padding-top:18px;
        border-top:1px solid #fde68a;
        justify-content:flex-end;
        flex-wrap:wrap;
    }

    .user-edit-page .btn{
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

    .user-edit-page .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }
    .user-edit-page .btn-submit:hover{
        background:#d97706;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    .user-edit-page .btn-outline{
        background:#ffffff;
        color:#111827;
        border:2px solid #fde68a;
    }
    .user-edit-page .btn-outline:hover{
        transform: translateY(-1px);
        border-color:#f59e0b;
    }

    /* alert */
    .user-edit-page .alert-danger{
        display:flex;
        gap:12px;
        align-items:flex-start;
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#7f1d1d;
        padding:14px 16px;
        border-radius:14px;
        margin-bottom:16px;
        font-weight:700;
        max-width:900px;
    }
    .user-edit-page .alert-icon{ line-height:1; }

    @media (max-width:768px){
        .user-edit-page .form-card{ padding:24px; }
        .user-edit-page .grid-2{ grid-template-columns:1fr; }
        .user-edit-page .btn-actions{ justify-content:flex-end; }
    }
    /* ===========================
   SCOPED CSS (ANTI NULAR)
   =========================== */

    .user-create-page .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        margin:0;
    }

    .user-create-page .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
        flex-wrap:wrap;
    }

    .user-create-page .card-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#111827;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .user-create-page .card-title i{ color:#f59e0b; }

    .user-create-page .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#6b7280;
        font-weight:600;
    }
    .user-create-page .card-subtitle i{ font-size:12px; }

    .user-create-page .grid-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .user-create-page .field{ margin:0; }

    .user-create-page .label{
        display:block;
        font-size:13px;
        font-weight:600;
        color:#6b7280;
        margin-bottom:8px;
    }

    .user-create-page .input,
    .user-create-page .select{
        width:100%;
        padding:12px 14px;
        border:1.8px solid #fde68a;
        border-radius:14px;
        background:#fff;
        color:#111827;
        font-weight:600;
        outline:none;
        transition:.15s ease;
        font-size:14px;
    }

    .user-create-page .input::placeholder{
        color:#9ca3af;
        font-weight:500;
    }

    .user-create-page .input:focus,
    .user-create-page .select:focus{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    .user-create-page .select{
        cursor:pointer;
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance:none;

        background:
            linear-gradient(45deg, transparent 50%, #9ca3af 50%),
            linear-gradient(135deg, #9ca3af 50%, transparent 50%),
            linear-gradient(to right, #fff, #fff);
        background-position:
            calc(100% - 20px) calc(1em + 2px),
            calc(100% - 15px) calc(1em + 2px),
            0 0;
        background-size:5px 5px,5px 5px,100% 100%;
        background-repeat:no-repeat;
        padding-right:44px;
    }

    /* placeholder select: abu & tidak bold */
    .user-create-page .select option.placeholder{
        color:#9ca3af;
        font-weight:500;
    }
    .user-create-page .select:has(option:checked.placeholder){
        color:#9ca3af;
        font-weight:500;
    }

    .user-create-page .btn-actions{
        display:flex;
        gap:10px;
        margin-top:18px;
        padding-top:18px;
        border-top:1px solid #fde68a;
        justify-content:flex-end;
        flex-wrap:wrap;
    }

    .user-create-page .btn{
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

    .user-create-page .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }
    .user-create-page .btn-submit:hover{
        background:#d97706;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    .user-create-page .btn-outline{
        background:#ffffff;
        color:#111827;
        border:2px solid #fde68a;
    }
    .user-create-page .btn-outline:hover{
        transform: translateY(-1px);
        border-color:#f59e0b;
    }

    .user-create-page .alert-danger{
        display:flex;
        gap:12px;
        align-items:flex-start;
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#7f1d1d;
        padding:14px 16px;
        border-radius:14px;
        margin-bottom:16px;
        font-weight:700;
        max-width:900px;
    }

    @media (max-width:768px){
        .user-create-page .form-card{ padding:24px; }
        .user-create-page .grid-2{ grid-template-columns:1fr; }
        .user-create-page .btn-actions{ justify-content:flex-end; }
    }
    .page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    }
    .pill-wrap{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        align-items:center;
    }
    .pill{
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:999px;
        padding:10px 12px;
        display:flex;
        gap:10px;
        align-items:center;
        font-size:13px;
        max-width:820px;
    }
    .pill.big{ padding:12px 14px; }

    /* ✅ kurangi tebal */
    .pill-label{ color:#000; font-weight:600; flex-shrink:0; }
    .pill-value{
        color:#000;
        font-weight:500;
        min-width:0;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .btn-back{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 14px;
        border-radius:999px;
        background:#ffffff;
        border:1px solid #fde68a;
        color:#000;
        text-decoration:none;
        font-weight:600;
        font-size:13px;
        transition:all .2s ease;
    }
    .btn-back:hover{
        transform: translateY(-1px);
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.14);
    }

    .pill-top-wrap{
        max-width:1800px;
        margin: 0 auto 14px;
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .card{
        max-width:1800px;
        margin:0 auto;
        background:#fff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:18px 20px;
        box-shadow:0 4px 16px rgba(0,0,0,0.06);
    }
    .card-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        margin-bottom:10px;
    }

    /* ✅ judul tetap agak tegas, tapi tidak “black” */
    .card h3{
        margin:0;
        font-size:16px;
        font-weight:700;
        color:#000;
    }
    .muted{ color:#000; font-weight:400; }

    .badge{
        display:inline-flex;
        align-items:center;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:600;
        border:1px solid #fde68a;
        background:#fffbeb;
        color:#000;
    }
    .badge-ok{
        background:#ecfdf5;
        border-color:#86efac;
        color:#065f46;
    }
    .badge-warn{
        background:#fef2f2;
        border-color:#fecaca;
        color:#000;
    }
    .badge-neutral{
        background:#fffef5;
        border-color:#fde68a;
        color:#000;
    }

    .divider{
        height:1px;
        background:#f3f4f6;
        margin:14px 0;
    }

    .conclusion-line{
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:8px 10px;
        padding:10px 12px;
        border:1px solid #fde68a;
        border-radius:12px;
        background:#fffef5;
    }
    .conclusion-label{
        font-size:12px;
        font-weight:600;
        color:#000;
        text-transform:uppercase;
        letter-spacing:.3px;
    }
    .conclusion-main{
        font-size:14px;
        font-weight:600;
        color:#000;
    }
    .conclusion-pct{
        font-weight:600;
        color:#000;
        margin-left:6px;
    }
    .conclusion-meta{
        font-weight:500;
        color:#000;
    }
    .muted-inline{
        font-weight:400;
        color:#000;
    }

    .summary-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }
    .summary-box{
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:12px 14px;
    }
    .summary-title{
        font-size:12px;
        color:#000;
        font-weight:600;
        margin-bottom:8px;
    }
    .summary-sub{
        margin-top:8px;
        font-size:13px;
        color:#000;
        font-weight:400;
    }

    .top3{ display:flex; flex-direction:column; gap:10px; }

    .top3-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:12px 12px;
        border:1px solid #fde68a;
        border-radius:14px;
        background:#ffffff;
    }

    /* ✅ clickable */
    .top3-row.clickable{
        width:100%;
        text-align:left;
        cursor:pointer;
    }
    .top3-row.clickable:focus{
        outline:none;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    .top3-row.is-top{
        background:#fffbeb;
        border-color:#f59e0b;
        box-shadow:0 10px 22px rgba(245,158,11,0.12);
    }
    .top3-left{ display:flex; align-items:center; gap:12px; min-width:0; }
    .top3-left .rank{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:42px;
        height:36px;
        border-radius:12px;
        background:#fffef5;
        border:1px solid #fde68a;
        font-weight:600;
        color:#000;
        flex-shrink:0;
    }
    .top3-left .who{ display:flex; flex-direction:column; gap:4px; min-width:0; }
    .top3-left .code{
        display:inline-flex;
        width:max-content;
        padding:3px 8px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#000;
        font-weight:600;
        font-size:12px;
    }
    .top3-left .name{
        color:#000;
        font-weight:500;
        font-size:13px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        max-width:360px;
    }
    .top3-right{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap:2px;
        flex-shrink:0;
        min-width:110px;
    }
    .top3-right .val{ font-weight:600; color:#000; font-size:13px; }
    .top3-right .pct{ font-weight:500; color:#000; font-size:12px; }

    .chips{ display:flex; flex-direction:column; gap:10px; }
    .chip{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:12px 14px;
    }
    .chip-code{
        display:inline-flex;
        padding:4px 10px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#000;
        font-weight:600;
        font-size:12px;
        white-space:nowrap;
    }
    .chip-name{
        flex:1;
        color:#000;
        font-weight:400;
        min-width:0;
    }
    .chip-cf{
        white-space:nowrap;
        color:#000;
        font-weight:500;
    }

    .table-wrap{
        overflow:auto;
        border-radius:12px;
        border:1px solid #fde68a;
    }
    table{ width:100%; border-collapse:collapse; background:#fff; }
    thead th{
        text-align:left;
        font-size:13px;
        color:#000;
        font-weight:600;
        padding:12px 12px;
        background:#fffbeb;
        border-bottom:1px solid #fde68a;
    }
    tbody td{
        padding:12px 12px;
        border-bottom:1px solid #f3f4f6;
        color:#000;
        font-weight:400;
    }
    .tcode{
        display:inline-flex;
        padding:4px 10px;
        border-radius:999px;
        background:#fffef5;
        border:1px solid #fde68a;
        font-weight:600;
        color:#000;
        font-size:12px;
    }

    /* ✅ baris top tetap sedikit lebih tegas */
    .top-row{ background:#fff7ed; }
    .top-row td{ font-weight:600; }

    /* ✅ modal solusi */
    .modal-backdrop{
        position:fixed;
        inset:0;
        background:rgba(0,0,0,0.35);
        display:none;              /* ✅ default: TERTUTUP */
        align-items:center;
        justify-content:center;
        padding:18px;
        z-index:9999;
    }

    .modal-backdrop.is-open{
        display:flex;              /* ✅ muncul saat diklik */
    }

    .modal-card{
        width:min(760px, 100%);
        background:#fff;
        border-radius:16px;
        border:1px solid #fde68a;
        box-shadow:0 20px 50px rgba(0,0,0,0.18);
        overflow:hidden;
    }
    .modal-head{
        display:flex;
        justify-content:space-between;
        gap:14px;
        padding:14px 16px;
        background:#fffbeb;
        border-bottom:1px solid #fde68a;
    }
    .modal-kode{
        font-weight:700;
        color:#000;
        font-size:12px;
    }
    .modal-title{
        font-weight:700;
        color:#000;
        font-size:15px;
    }
    .modal-close{
        border:1px solid #fde68a;
        background:#fff;
        border-radius:10px;
        width:36px;
        height:36px;
        cursor:pointer;
        font-weight:700;
        color:#000;
    }
    .modal-body{
        padding:14px 16px;
        color:#000;
        font-weight:400;
        line-height:1.6;
        white-space:pre-wrap;
    }

    @media (max-width: 900px){
        .summary-grid{ grid-template-columns:1fr; }
        .chip{ flex-direction:column; align-items:flex-start; }
        .chip-cf{ width:100%; }

        .top3-right{ align-items:flex-start; }
        .top3-row{ flex-direction:column; align-items:flex-start; }
        .top3-left .name{ max-width:100%; white-space:normal; }
        .pill{ max-width:100%; }
    }
    .page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    }
    .pill-wrap{ display:flex; gap:10px; flex-wrap:wrap; }
    .pill{
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:999px;
        padding:10px 12px;
        display:flex;
        gap:10px;
        align-items:center;
        font-size:13px;
        max-width:820px;
    }
    .pill-label{ color:#000; font-weight:600; flex-shrink:0; }
    .pill-value{
        color:#000;
        font-weight:600;
        min-width:0;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .card{
        max-width:1800px;
        margin:0 auto;
        background:#fff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:18px 20px;
        box-shadow:0 4px 16px rgba(0,0,0,0.06);
    }
    .card-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        margin-bottom:10px;
    }
    .card h3{
        margin:0;
        font-size:16px;
        font-weight:600;
        color:#000;
    }
    .badge{
        display:inline-flex;
        align-items:center;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
        border:1px solid #fde68a;
        background:#fffbeb;
        color:#000;
    }
    .badge-neutral{
        background:#fffef5;
        border-color:#fde68a;
        color:#000;
    }
    .divider{
        height:1px;
        background:#f3f4f6;
        margin:14px 0;
    }
    .filter-form{
        display:flex;
        gap:12px;
        align-items:flex-end;
        flex-wrap:wrap;
        margin:0;
    }
    .filter-field{
        display:flex;
        flex-direction:column;
        gap:6px;
        min-width:180px;
    }
    .filter-field label{
        font-size:12px;
        font-weight:600;
        color:#000;
    }
    .filter-field input{
        padding:10px 12px;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#fff;
        color:#000;
        font-weight:700;
        outline:none;
    }
    .filter-field input:focus{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.14);
    }
    .filter-actions{
        display:flex;
        gap:10px;
        align-items:flex-end;
        margin-left:0;
    }

    .filter-meta{
        margin-top:10px;
        color:#000;
        font-weight:600;
        font-size:13px;
    }

    .btn-primary{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:11px 18px;
        border-radius:12px;
        background:#f59e0b;
        color:#fff;
        text-decoration:none;
        border:none;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 8px 20px rgba(245,158,11,0.22);
        transition:all .15s ease;
        white-space:nowrap;
    }
    .btn-primary:hover{ background:#d97706; transform: translateY(-1px); }
    .btn-ghost{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:11px 18px;
        border-radius:12px;
        background:#ffffff;
        color:#000;
        text-decoration:none;
        border:1px solid #fde68a;
        font-weight:900;
        transition:all .15s ease;
        white-space:nowrap;
    }
    .btn-ghost:hover{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.14);
        transform: translateY(-1px);
    }
    .btn-sm{ padding:10px 14px; border-radius:999px; font-weight:600; }

    /* ✅ tombol di tengah kolom */
    .btn-center{ margin:0 auto; }

    .flash-success{
        background:#ecfdf5;
        border:1px solid #86efac;
        color:#065f46;
        font-weight:600;
        padding:12px 14px;
        border-radius:14px;
        box-shadow:0 10px 26px rgba(0,0,0,0.06);
    }

    .empty-state{
        padding:18px 14px;
        border:1px dashed #fde68a;
        border-radius:14px;
        background:#fffef5;
        text-align:center;
    }
    .empty-ico{ font-size:44px; margin-bottom:10px; }
    .empty-title{ font-weight:600; color:#000; font-size:14px; }
    .empty-sub{ color:#000; font-weight:600; font-size:13px; margin-top:4px; }

    .table-wrap{ overflow:auto; border-radius:12px; border:1px solid #fde68a; }
    table{ width:100%; border-collapse:collapse; background:#fff; }
    thead th{
        text-align:left;
        font-size:13px;
        color:#000;
        font-weight:600;
        padding:12px 12px;
        background:#fffbeb;
        border-bottom:1px solid #fde68a;
        white-space:nowrap;
    }
    tbody td{
        padding:14px 12px;
        border-bottom:1px solid #f3f4f6;
        color:#000;
        font-weight:500;
        vertical-align:top;
    }
    .row-hover:hover{ background:#fffbeb; }

    .dt-date{ font-size:13px; color:#000; font-weight:600;}
    .dt-time{ margin-top:4px; font-size:13px; color:#d97706; font-weight:600; }
    .judul{ font-size:14px; font-weight:600; color:#000; }

    /* Stack pills */
    .mini-pills{
        margin-top:0;
        display:flex;
        flex-direction:column;
        gap:10px;
        align-items:flex-start;
    }
    .mini-pill{
        width:100%;
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:999px;
        padding:10px 12px;
        display:flex;
        gap:10px;
        align-items:center;
        font-size:12.5px;
    }
    .mini-label{
        color:#000;
        font-weight:600;
        flex-shrink:0;
        min-width:58px;
    }
    .mini-value{
        color:#000;
        font-weight:600;
        min-width:0;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    /* Pagination */
    .pagination-wrap nav[role="navigation"]{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        padding:12px 14px;
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:14px;
    }
    .pagination-wrap p{
        margin:0;
        color:#000;
        font-size:13px;
        font-weight:600;
        line-height:1.4;
    }
    .pagination-wrap .inline-flex{ display:inline-flex !important; gap:8px !important; flex-wrap:wrap; }
    .pagination-wrap a{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:9px 14px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#000;
        font-size:13px;
        font-weight:600;
        text-decoration:none;
        transition:all .18s ease;
        white-space:nowrap;
    }
    .pagination-wrap a:hover{
        background:#fffef5;
        border-color:#f59e0b;
        transform:translateY(-1px);
    }
    .pagination-wrap span[aria-disabled="true"] span{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:9px 14px;
        border-radius:999px;
        background:#f3f4f6;
        border:1px solid #e5e7eb;
        color:#6b7280;
        font-size:13px;
        font-weight:600;
        white-space:nowrap;
    }
    .pagination-wrap span[aria-current="page"] span{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:9px 14px;
        border-radius:999px;
        background:#f59e0b;
        border:1px solid #f59e0b;
        color:#ffffff;
        font-size:13px;
        font-weight:600;
        white-space:nowrap;
    }

    @media (max-width: 900px){
        .filter-actions{ margin-left:0; width:100%; justify-content:flex-start; }
        thead th{ white-space:normal; }
    }
    @media (max-width:768px){
        .pagination-wrap nav[role="navigation"]{ justify-content:center; text-align:center; }
    }
        .alert-success{
        display:flex;
        align-items:center;
        gap:10px;
        background:#ecfdf3;
        border:1px solid #a7f3d0;
        color:#166534;
        padding:12px 16px;
        border-radius:12px;
        font-size:14px;
        margin-bottom:14px;
        font-weight:600;
    }
    .alert-error{
        display:flex;
        align-items:center;
        gap:10px;
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#991b1b;
        padding:12px 16px;
        border-radius:12px;
        font-size:14px;
        margin-bottom:22px;
        font-weight:600;
    }

    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
    }

    .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
    }

    .card-title{
        margin:0;
        font-size:18px;
        font-weight:700;
        color:#000;
        display:flex;
        align-items:center;
        gap:10px;
    }

    .card-title i{ color:#f59e0b; }

    .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#000;
        opacity:.85;
    }

    .card-subtitle i{ font-size:12px; }

    .table-wrap{
        overflow-x:auto;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#ffffff;
    }

    .table{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        background:white;
    }

    .table thead{ background:#fff9c4; }

    .table th,
    .table td{
        padding:12px 12px;
        text-align:left;
        vertical-align:top;
        border-bottom:1px solid #fde68a;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff9c4; }

    .action-group{
        display:flex;
        gap:10px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .empty{
        text-align:center;
        padding:22px;
        color:#000;
        font-weight:600;
        background:#fffbeb;
    }

    .btn{
        padding:13px 28px;
        border:none;
        border-radius:10px;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.3);
    }

    .btn-submit:hover{
        background:#d97706;
        transform:translateY(-2px);
        box-shadow:0 6px 16px rgba(245,158,11,0.4);
    }

    .btn-mini{
        border:none;
        border-radius:999px;
        padding:8px 14px;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-edit{
        background:#f59e0b;
        color:#fff;
        box-shadow:0 4px 12px rgba(245,158,11,.22);
    }

    .btn-edit:hover{
        background:#d97706;
        transform:translateY(-1px);
    }

    .btn-delete{
        background:#fef2f2;
        color:#b91c1c;
        border:1px solid #fecaca;
    }

    .btn-delete:hover{
        background:#fee2e2;
        transform:translateY(-1px);
    }

    /* ✅ mirip gejala: jangan not-allowed biar tidak ada tanda bulat merah */
    .btn-disabled{
        opacity:.75;
        cursor:pointer;
    }
    .btn-disabled:hover{
        transform:none;
        background:#fef2f2;
    }

    /* ===================== MODAL ===================== */
    .modal-overlay{
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
    }

    .modal-box{
        background: #fff;
        padding: 22px 24px;
        border-radius: 16px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,.22);
        text-align: center;
        border: 1px solid #fde68a;
        animation: modalPop .14s ease-out;
    }

    @keyframes modalPop {
        from { transform: scale(.97); opacity: .6; }
        to   { transform: scale(1); opacity: 1; }
    }

    .modal-box h3{
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }

    .modal-box p{
        margin: 0;
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
    }

    .modal-actions{
        margin-top: 18px;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-cancel{
        background:#f3f4f6;
        color:#111827;
    }
    .btn-cancel:hover{
        background:#e5e7eb;
        transform: translateY(-1px);
    }

    .btn-danger{
        background:#dc2626;
        color:#fff;
        box-shadow:0 6px 18px rgba(220,38,38,.18);
    }
    .btn-danger:hover{
        background:#b91c1c;
        transform: translateY(-1px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
        .modal-box{ max-width: 520px; }
    }
        .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    /* Alert Styling */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-icon {
        font-size: 20px;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .alert-content ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-content li {
        margin-bottom: 4px;
        font-size: 14px;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 28px;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #000;
        margin-bottom: 10px;
    }

    .form-label i {
        color: #f59e0b;
        font-size: 16px;
    }

    .required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 2px;
    }

    /* Form Input */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #fffbeb;
        border-radius: 10px;
        font-size: 14px;
        color: #000;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .form-input::placeholder {
        color: #a0826d;
        opacity: 0.6;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }

    .input-readonly {
        background: #fef3c7;
        font-weight: 600;
        cursor: not-allowed;
        color: #000;
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #000;
        margin-top: 8px;
        opacity: 0.8;
    }

    .input-hint i {
        font-size: 12px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 1px solid #fde68a;
    }

    .btn {
        padding: 13px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn i {
        font-size: 14px;
    }

    .btn-submit {
        background: #f59e0b; /* solid */
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #000;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .form-card {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
        :root{ --sidebar-w: 260px; }

    .stats-banner{ display:flex; gap:14px; margin-bottom:18px; max-width:1800px; margin-left:auto; margin-right:auto; }
    .stat-card{ flex:1; background:#fff; border:1px solid #fde68a; border-radius:14px; padding:14px 18px; box-shadow:0 4px 16px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .stat-number{ font-size:26px; font-weight:900; color:#111827; line-height:1; }
    .stat-label{ font-size:13px; color:#111827; opacity:.75; font-weight:800; text-align:right; }

    .form-card{
        background:#fff; border-radius:16px; padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08); border:1px solid #fde68a;
        max-width:1800px; margin:0 auto; font-size:14px; color:#374151;
        padding-bottom: 170px;
        position: relative;
    }

    .form-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:16px; flex-wrap:wrap; }
    .form-label{ margin:0; font-size:18px; font-weight:900; color:#111827; display:flex; align-items:center; gap:10px; }
    .form-label i{ color:#f59e0b; }
    .required-mark{ color:#ef4444; margin-left:4px; font-weight:900; }

    .hint-pill{ display:inline-flex; align-items:center; gap:8px; padding:10px 12px; border-radius:999px; background:#fffbeb; border:1px solid #fde68a; color:#111827; font-weight:800; font-size:13px; white-space:nowrap; }
    .hint-pill i{ color:#f59e0b; }

    .info-box{ background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:16px 18px; margin-bottom:18px; display:flex; align-items:flex-start; gap:12px; }
    .info-icon{ width:36px;height:36px;border-radius:12px; background:#fff9c4; border:1px solid #fde68a; display:flex;align-items:center;justify-content:center; font-size:18px;flex-shrink:0; }
    .info-box-text{ font-size:14px; color:#111827; opacity:.8; line-height:1.6; font-weight:600; }

    .gejala-container{ display:flex; flex-direction:column; gap:10px; }
    .gejala-row{
        display:flex; align-items:center; justify-content:space-between; gap:14px;
        padding:14px 16px; background:#fffef5; border:1px solid #fde68a;
        border-radius:14px; transition:all .18s ease;
    }
    .gejala-row:hover{ background:#fff9c4; transform: translateY(-1px); box-shadow:0 6px 18px rgba(245,158,11,0.12); }
    .gejala-row.active{ background:#fffbeb; border-color:#f59e0b; box-shadow:0 8px 22px rgba(245,158,11,0.18); }

    .checkbox-wrapper{ display:flex; align-items:center; gap:12px; flex:1; margin:0; cursor:pointer; user-select:none; min-width:0; }
    .cb-gejala{ width:20px;height:20px; cursor:pointer; accent-color:#f59e0b; flex-shrink:0; margin-top:0; }

    .gejala-text{ display:flex; align-items:center; gap:12px; line-height:1.4; white-space:nowrap; }
    .gejala-code{ display:inline-flex; align-items:center; padding:3px 8px; border-radius:999px; background:#fff; border:1px solid #fde68a; color:#111827; font-weight:900; font-size:11px; white-space:nowrap; letter-spacing:0.3px; flex-shrink:0; }
    .gejala-name{ font-size:15px; color:#111827; font-weight:400; line-height:1.35; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }

    .cf-buttons{ display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end; min-width:260px; opacity:.55; pointer-events:none; transition:opacity .15s ease; }
    .gejala-row.active .cf-buttons{ opacity:1; pointer-events:auto; }

    .cf-btn{ border:1.8px solid #fde68a; background:#fff; color:#111827; font-weight:900; font-size:13px; padding:10px 12px; border-radius:12px; cursor:pointer; min-width:44px; text-align:center; transition:all .15s ease; }
    .cf-btn:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .cf-btn.selected{ background:#f59e0b; border-color:#f59e0b; color:#fff; box-shadow:0 8px 20px rgba(245,158,11,0.25); }
    .cf-btn.need{ border-color:#ef4444 !important; box-shadow:0 0 0 4px rgba(239,68,68,0.12) !important; }

    .btn{ padding:12px 18px; border:none; border-radius:12px; font-size:14px; font-weight:900; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:10px; transition:all .2s ease; white-space:nowrap; }
    .btn-submit{ background:#f59e0b; color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); letter-spacing:0.3px; }
    .btn-submit:hover{ background:#d97706; transform: translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }

    .error-message{ display:flex; align-items:center; gap:10px; color:#7f1d1d; background:#fef2f2; padding:12px 14px; border-radius:12px; margin-top:14px; font-size:14px; border:1px solid #fecaca; font-weight:800; }

    .bottom-bar{
        position: fixed; bottom: 0; left: var(--sidebar-w); width: calc(100% - var(--sidebar-w));
        z-index: 9999; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
        border-top: 1px solid #fde68a; padding: 10px 14px; box-sizing: border-box;
    }
    .bottom-bar-inner{ max-width: 1800px; margin: 0 auto; display:flex; align-items:center; justify-content:space-between; gap:14px; }
    .bb-left{ min-width:0; flex:1; }
    .bb-title{ font-size:13px; font-weight:800; color:#111827; line-height:1.2; }
    .bb-sub{ margin-top:2px; font-size:12px; font-weight:600; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:900px; }
    .bb-progress{ margin-top:8px; display:flex; align-items:center; gap:10px; }
    .bb-bar{ height:8px; flex:1; min-width:160px; background:#fff7ed; border:1px solid #fde68a; border-radius:999px; overflow:hidden; }
    .bb-fill{ height:100%; width:0%; background:#f59e0b; border-radius:999px; transition:width .18s ease; }
    .bb-meta{ display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; color:#111827; opacity:.9; white-space:nowrap; }
    .bb-meta .dot{ opacity:.6; }
    .bb-badge{ display:inline-flex; padding:4px 8px; border-radius:999px; background:#fffbeb; border:1px solid #fde68a; font-weight:800; }
    .bottom-submit{ padding:11px 16px; border-radius:14px; box-shadow:0 10px 24px rgba(0,0,0,0.12); width:auto; flex-shrink:0; }

    @media (max-width: 1024px){ .bottom-bar{ left:0; width:100%; } }
    @media (max-width: 768px){
        .form-card{ padding:24px; padding-bottom: 220px; }
        .stats-banner{ flex-direction:column; }
        .gejala-row{ flex-direction:column; align-items:stretch; }
        .cf-buttons{ min-width:100%; justify-content:flex-start; }
        .gejala-text{ flex-wrap:wrap; }
        .gejala-name{ white-space:normal; }
        .bottom-bar-inner{ flex-direction:column; align-items:stretch; }
        .bb-sub{ max-width:100%; white-space:normal; }
        .bb-progress{ flex-direction:column; align-items:stretch; }
        .bb-meta{ justify-content:space-between; width:100%; }
        .bottom-submit{ width:100%; justify-content:center; }
    }

    /* Search */
    .search-wrap{ margin: 8px 0 14px; }
    .search-box{ display:flex; align-items:center; gap:10px; padding:12px 14px; border:1px solid #fde68a; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(0,0,0,0.04); }
    .search-box i{ color:#f59e0b; font-size:14px; }
    .search-box input{ flex:1; border:none; outline:none; font-size:14px; font-weight:700; color:#111827; background:transparent; }
    .search-box input::placeholder{ color:#9ca3af; font-weight:700; }
    .search-clear{ border:1px solid #fde68a; background:#fffbeb; color:#111827; width:36px; height:36px; border-radius:12px; cursor:pointer; font-weight:900; display:inline-flex; align-items:center; justify-content:center; transition:all .15s ease; }
    .search-clear:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .search-meta{ margin-top:8px; font-size:12px; font-weight:700; color:#6b7280; }

    /* Toast */
    .toast{
        position: sticky;
        top: 12px;
        z-index: 50;
        display:flex;
        align-items:center;
        gap:10px;
        padding:12px 14px;
        margin-bottom:14px;
        border-radius:14px;
        border:1px solid #fecaca;
        background:#fef2f2;
        color:#7f1d1d;
        font-weight:800;
        box-shadow:0 10px 26px rgba(0,0,0,0.08);
    }
    .toast.ok{
        border-color:#86efac;
        background:#ecfdf5;
        color:#065f46;
    }
    .toast-icon{ width:28px;height:28px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#fff; border:1px solid rgba(0,0,0,0.06); flex-shrink:0; }
    .toast-text{ flex:1; min-width:0; }
    .toast-x{
        border:1px solid rgba(0,0,0,0.08);
        background:#fff;
        width:34px;height:34px;
        border-radius:12px;
        cursor:pointer;
        font-weight:900;
        display:flex; align-items:center; justify-content:center;
    }
    .page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    }
    .pill-wrap{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }
    .pill{
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:999px;
        padding:10px 12px;
        display:flex;
        gap:10px;
        align-items:center;
        font-size:13px;
        max-width:820px;
    }
    .pill-label{ color:#92400e; font-weight:800; flex-shrink:0; }
    .pill-value{
        color:#78350f;
        font-weight:800;
        min-width:0;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    /* card */
    .card{
        max-width:1800px;
        margin:0 auto;
        background:#fff;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:18px 20px;
        box-shadow:0 4px 16px rgba(0,0,0,0.06);
    }
    .card-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        margin-bottom:10px;
    }
    .card h3{
        margin:0;
        font-size:16px;
        font-weight:800;
        color:#78350f;
    }
    .muted{ color:#92400e; font-weight:500; }

    .badge{
        display:inline-flex;
        align-items:center;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
        border:1px solid #fde68a;
        background:#fffbeb;
        color:#78350f;
    }
    .badge-ok{
        background:#ecfdf5;
        border-color:#86efac;
        color:#065f46;
    }
    .badge-warn{
        background:#fef2f2;
        border-color:#fecaca;
        color:#7f1d1d;
    }
    .badge-neutral{
        background:#fffef5;
        border-color:#fde68a;
        color:#92400e;
    }

    .divider{
        height:1px;
        background:#f3f4f6;
        margin:14px 0;
    }

    /* Kesimpulan ringkas */
    .conclusion-line{
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:8px 10px;
        padding:10px 12px;
        border:1px solid #fde68a;
        border-radius:12px;
        background:#fffef5;
    }
    .conclusion-label{
        font-size:12px;
        font-weight:900;
        color:#92400e;
        text-transform:uppercase;
        letter-spacing:.3px;
    }
    .conclusion-main{
        font-size:14px;
        font-weight:900;
        color:#78350f;
    }
    .conclusion-pct{
        font-weight:800;
        color:#92400e;
        margin-left:6px;
    }
    .conclusion-meta{
        font-weight:700;
        color:#92400e;
    }
    .muted-inline{
        font-weight:600;
        color:#92400e;
    }

    /* summary grid */
    .summary-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }
    .summary-box{
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:12px 14px;
    }
    .summary-title{
        font-size:12px;
        color:#92400e;
        font-weight:800;
        margin-bottom:8px;
    }
    .summary-sub{
        margin-top:8px;
        font-size:13px;
        color:#92400e;
        font-weight:500;
    }

    /* Top 3 list */
    .top3{ display:flex; flex-direction:column; gap:10px; }

    .top3-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:12px 12px;
        border:1px solid #fde68a;
        border-radius:14px;
        background:#ffffff;
    }

    /* ✅ clickable */
    .top3-row.clickable{
        width:100%;
        text-align:left;
        cursor:pointer;
    }
    .top3-row.clickable:focus{
        outline:none;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    .top3-row.is-top{
        background:#fffbeb;
        border-color:#f59e0b;
        box-shadow:0 10px 22px rgba(245,158,11,0.12);
    }
    .top3-left{
        display:flex;
        align-items:center;
        gap:12px;
        min-width:0;
    }
    .top3-left .rank{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:42px;
        height:36px;
        border-radius:12px;
        background:#fffef5;
        border:1px solid #fde68a;
        font-weight:800;
        color:#78350f;
        flex-shrink:0;
    }
    .top3-left .who{
        display:flex;
        flex-direction:column;
        gap:4px;
        min-width:0;
    }
    .top3-left .code{
        display:inline-flex;
        width:max-content;
        padding:3px 8px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#78350f;
        font-weight:800;
        font-size:12px;
    }
    .top3-left .name{
        color:#78350f;
        font-weight:800;
        font-size:13px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        max-width:360px;
    }
    .top3-right{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap:2px;
        flex-shrink:0;
        min-width:110px;
    }
    .top3-right .val{ font-weight:900; color:#78350f; font-size:13px; }
    .top3-right .pct{ font-weight:800; color:#92400e; font-size:12px; }

    /* gejala chips */
    .chips{ display:flex; flex-direction:column; gap:10px; }
    .chip{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:12px 14px;
    }
    .chip-code{
        display:inline-flex;
        padding:4px 10px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#78350f;
        font-weight:800;
        font-size:12px;
        white-space:nowrap;
    }
    .chip-name{
        flex:1;
        color:#78350f;
        font-weight:600;
        min-width:0;
    }
    .chip-cf{
        white-space:nowrap;
        color:#92400e;
        font-weight:600;
    }

    /* table */
    .table-wrap{
        overflow:auto;
        border-radius:12px;
        border:1px solid #fde68a;
    }
    table{ width:100%; border-collapse:collapse; background:#fff; }
    thead th{
        text-align:left;
        font-size:13px;
        color:#92400e;
        font-weight:800;
        padding:12px 12px;
        background:#fffbeb;
        border-bottom:1px solid #fde68a;
    }
    tbody td{
        padding:12px 12px;
        border-bottom:1px solid #f3f4f6;
        color:#78350f;
        font-weight:500;
    }
    .tcode{
        display:inline-flex;
        padding:4px 10px;
        border-radius:999px;
        background:#fffef5;
        border:1px solid #fde68a;
        font-weight:800;
        color:#78350f;
        font-size:12px;
    }
    .top-row{ background:#fff7ed; }
    .top-row td{ font-weight:700; }
    .modal-head{
        display:flex;
        justify-content:space-between;
        gap:14px;
        padding:14px 16px;
        background:#fffbeb;
        border-bottom:1px solid #fde68a;
    }
    .modal-kode{
        font-weight:900;
        color:#78350f;
        font-size:12px;
    }
    .modal-title{
        font-weight:900;
        color:#78350f;
        font-size:15px;
    }
    .modal-close{
        border:1px solid #fde68a;
        background:#fff;
        border-radius:10px;
        width:36px;
        height:36px;
        cursor:pointer;
        font-weight:900;
        color:#78350f;
    }
    .modal-body{
        padding:14px 16px;
        color:#78350f;
        font-weight:500;
        line-height:1.6;
        white-space:pre-wrap;
    }

    @media (max-width: 900px){
        .summary-grid{ grid-template-columns:1fr; }
        .chip{ flex-direction:column; align-items:flex-start; }
        .chip-cf{ width:100%; }

        .top3-right{ align-items:flex-start; }
        .top3-row{ flex-direction:column; align-items:flex-start; }
        .top3-left .name{ max-width:100%; white-space:normal; }
        .pill{ max-width:100%; }
    }
    .btn-mini{
        border:none;
        border-radius:999px;
        padding:8px 14px;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-detail{
        background:#fff9c4;
        color:#111827; /* hitam */
    }

    .btn-detail:hover{
        background:#fff9c4;
        transform: translateY(-1px);
    }
        .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    /* Alert Styling */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b; /* tetap merah (error) */
    }
    .alert-icon { font-size: 20px; margin-top: 2px; }
    .alert-content { flex: 1; }
    .alert-content strong { display: block; margin-bottom: 8px; font-weight: 600; }
    .alert-content ul { margin: 0; padding-left: 20px; }
    .alert-content li { margin-bottom: 4px; font-size: 14px; }

    /* Form Group */
    .form-group { margin-bottom: 28px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #111827; /* hitam */
        margin-bottom: 10px;
    }
    .form-label i { color: #f59e0b; font-size: 16px; }

    /* Input */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #fffbeb;
        border-radius: 10px;
        font-size: 14px;
        color: #111827; /* hitam */
        transition: all 0.2s ease;
        font-family: inherit;
        outline: none;
    }
    .form-input:focus {
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .input-readonly {
        background: #fef3c7;
        font-weight: 600;
        cursor: not-allowed;
        color: #111827; /* hitam */
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #111827; /* hitam */
        margin-top: 8px;
        opacity: 0.7;
    }
    .input-hint i { font-size: 12px; }

    /* Section */
    .gejala-section {
        background: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 22px;
        margin-top: 10px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 800;
        color: #111827; /* hitam */
        margin-bottom: 16px;
    }
    .section-label i { color: #f59e0b; }

    /* ✅ Samain dengan CF: layout row-wrap/col look */
    .gejala-row{
        display:flex;
        gap:18px;
        align-items:flex-start;
        flex-wrap:wrap;
        background: transparent;  /* beda dari sebelumnya */
        padding: 0;               /* samain CF */
        border: 0;                /* samain CF */
        border-radius: 0;
        box-shadow: none;
    }

    .gejala-select{
        flex: 1;
        min-width: 280px;
    }

    .ds-select{
        flex: 1;
        min-width: 280px;
    }

    /* Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 1px solid #fde68a;
    }

    .btn {
        padding: 13px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn i { font-size: 14px; }

    .btn-submit {
        background: #f59e0b;
        color: #ffffff; /* tetap putih */
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #111827; /* hitam */
    }
    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .form-card { padding: 24px; }
        .gejala-row{ flex-direction:column; align-items:stretch; }
        .gejala-select, .ds-select{ min-width:unset; width:100%; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
        .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
    }

    .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
    }

    .card-title{
        margin:0;
        font-size:18px;
        font-weight:700;
        color:#111827;
        display:flex;
        align-items:center;
        gap:10px;
    }

    .card-title i{ color:#111827; }

    .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#111827;
        opacity:.8;
    }

    .card-subtitle i{ font-size:12px; }

    .table-wrap{
        overflow-x:auto;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#ffffff;
    }

    .table{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        background:white;
    }

    .table thead{
        background:#fff9c4;
    }

    .table th,
    .table td{
        padding:12px 12px;
        text-align:left;
        vertical-align:top;
        border-bottom:1px solid #fde68a;
        color:#111827;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff9c4; }

    .badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:54px;
        padding:6px 10px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        font-weight:700;
        color:#111827;
        font-size:13px;
    }

    .action-group{
        display:flex;
        gap:10px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .btn-mini{
        border:none;
        border-radius:999px;
        padding:7px 12px;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;

        appearance:none;
        -webkit-appearance:none;
    }
    .empty{
        text-align:center;
        padding:22px;
        color:#111827;
        font-weight:600;
        background:#fffbeb;
    }

    .btn{
        padding:13px 28px;
        border:none;
        border-radius:10px;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
    }

    .btn-cancel{
        background:#f3f4f6;
        color:#111827;
    }
    .btn-cancel:hover{
        background:#e5e7eb;
        transform:translateY(-2px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
    }
        .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    /* Alert Styling */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b; /* tetap merah (error) */
    }
    .alert-icon { font-size: 20px; margin-top: 2px; }
    .alert-content { flex: 1; }
    .alert-content strong { display: block; margin-bottom: 8px; font-weight: 600; }
    .alert-content ul { margin: 0; padding-left: 20px; }
    .alert-content li { margin-bottom: 4px; font-size: 14px; }

    /* Form Group */
    .form-group { margin-bottom: 28px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #111827; /* hitam */
        margin-bottom: 10px;
    }
    .form-label i { color: #f59e0b; font-size: 16px; }

    .required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 2px;
    }

    /* Input */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #fffbeb;
        border-radius: 10px;
        font-size: 14px;
        color: #111827; /* hitam */
        transition: all 0.2s ease;
        font-family: inherit;
        outline: none;
    }
    .form-input:focus {
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .input-readonly {
        background: #fef3c7;
        font-weight: 600;
        cursor: not-allowed;
        color: #111827; /* hitam */
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #111827; /* hitam */
        margin-top: 8px;
        opacity: 0.7;
    }
    .input-hint i { font-size: 12px; }

    /* Section */
    .gejala-section {
        background: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 22px;
        margin-top: 10px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 800;
        color: #111827; /* hitam */
        margin-bottom: 16px;
    }
    .section-label i { color: #f59e0b; }

    .row-wrap {
        display: flex;
        gap: 18px;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    .col {
        flex: 1;
        min-width: 280px;
    }

    /* Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 1px solid #fde68a;
    }

    .btn {
        padding: 13px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn i { font-size: 14px; }

    .btn-submit {
        background: #f59e0b;
        color: #ffffff; /* tetap putih */
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #111827; /* hitam */
    }
    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .form-card { padding: 24px; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
        .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    /* Alert */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-icon { font-size: 20px; margin-top: 2px; }
    .alert-content { flex: 1; }
    .alert-content strong { display: block; margin-bottom: 8px; font-weight: 600; }
    .alert-content ul { margin: 0; padding-left: 20px; }
    .alert-content li { margin-bottom: 4px; font-size: 14px; }

    /* Form */
    .form-group { margin-bottom: 28px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color:#000;
        margin-bottom: 10px;
    }

    .form-label i { color: #f59e0b; font-size: 16px; }

    .required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 2px;
    }

    /* ✅ Input dibuat polos (tanpa background kuning) */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #ffffff;           /* <--- ubah dari #fffbeb ke putih */
        border-radius: 10px;
        font-size: 14px;
        color:#000;
        transition: all 0.2s ease;
        font-family: inherit;
        outline: none;
    }

    .form-input:focus {
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color:#000;
        margin-top: 8px;
        opacity: 0.8;
    }

    .input-hint i { font-size: 12px; }

    /* Section gejala */
    .gejala-section {
        background: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 22px;
        margin-top: 10px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        color: #000;
        margin-bottom: 16px;
    }

    .section-label i { color: #f59e0b; }

    #gejala-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    /* ===============================
   FORCE TEXT COLOR → HITAM (#000)
   TANPA MERUSAK STYLING
   =============================== */

    body,
    p, span, div, li, td, th,
    .page-title,
    .page-subtitle,
    .form-label,
    .info-box-text,
    .gejala-name,
    .search-meta,
    .bb-title,
    .bb-sub,
    .stat-label,
    .stat-number,
    .card-title,
    .card-subtitle,
    .muted,
    .badge,
    .badge-neutral,
    .badge-warn,
    .badge-ok,
    .conclusion-label,
    .conclusion-main,
    .conclusion-meta,

    .top3-left .name,
    .top3-right .val,
    .top3-right .pct,
    .chip-name,
    .chip-cf,
    .table th,
    .table td,
    .pill-label,
    .pill-value,
    .modal-title,
    .modal-body,
    .profile-name {
        color: #000 !important;
    }
    .main-content :where(
    h1,h2,h3,h4,h5,h6,
    p,span,div,li,ul,ol,
    label,small,strong,b,em,
    td,th,a
    ){
    color:#000 !important;
    }
    .btn-primary{
    background:#f59e0b;   /* kuning */
    color:#ffffff !important;
}

.btn-primary:hover{
    background:#d97706;
    color:#ffffff !important;
}
/* ===============================
   DETAIL RIWAYAT DIAGNOSA
   TEKS LEBIH RINGAN
   =============================== */

/* paragraf & isi konten */
.card p,
.card li,
.card span,
.card td,
.card .muted,
.card .muted-inline{
    font-weight: 600 !important;
}

/* judul section (masih tegas tapi tidak berat) */
.card h3,
.summary-title,
.section-label{
    font-weight: 500 !important;
}

/* nama penyakit & deskripsi */
.top3-left .name,
.chip-name,
.summary-sub,
.conclusion-meta{
    font-weight: 550 !important;
}

/* angka persen & nilai */
.top3-right .val,
.top3-right .pct,
.conclusion-pct{
    font-weight: 600 !important;
}

/* label kecil */
.pill-label,
.badge,
.summary-title{
    font-weight: 600 !important;
}
    </style>

</head>
<body>
<div class="container">

    @php
        $role = Auth::check() ? (Auth::user()->role ?? null) : null;
    @endphp

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

        {{-- DATA (PEMILIK + PAKAR SAJA) --}}
        @if(in_array($role, ['pemilik','pakar']))
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
        @endif

        {{-- DIAGNOSA --}}
        <div class="sidebar-section">
            <div class="sidebar-title">DIAGNOSA</div>

            {{-- ✅ Diagnosa Penyakit hanya untuk pemilik + peternak (pakar tidak tampil) --}}
            @if(in_array($role, ['pemilik','peternak']))
                <a href="{{ route('diagnosa.index') }}"
                class="menu-item {{ request()->is('diagnosa*') ? 'active' : '' }}">
                    <i class="fas fa-heartbeat"></i>
                    <span class="label">Diagnosa Penyakit</span>
                </a>
            @endif

            {{-- Riwayat: pemilik + pakar + peternak --}}
            @if(in_array($role, ['pemilik','pakar','peternak']))
                <a href="{{ route('riwayat-diagnosa.index') }}"
                class="menu-item {{ request()->is('riwayat-diagnosa*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span class="label">Riwayat Diagnosa</span>
                </a>
            @endif
        </div>


        {{-- ROLE (PEMILIK SAJA) --}}
        @if($role === 'pemilik')
            <div class="sidebar-section">
                <div class="sidebar-title">ROLE</div>

                <a href="{{ route('users.index') }}"
                   class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="label">Pengguna</span>
                </a>
            </div>
        @endif

        {{-- LOGOUT PALING BAWAH SIDEBAR --}}
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
    {{-- END SIDEBAR --}}

    {{-- ✅ INI YANG HILANG DI FILE KAMU: MAIN CONTENT --}}
    <div class="main-content">

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
    {{-- END MAIN CONTENT --}}

</div>
</body>
</html>
