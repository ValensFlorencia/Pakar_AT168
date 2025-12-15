@extends('layouts.app')

@section('title', 'Data Users')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Users</h1>
    <p class="page-subtitle">Kelola akun dan role (admin, pakar, user).</p>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-danger">
        ⚠️ {{ session('error') }}
    </div>
@endif

<div class="form-card" style="max-width:1800px;">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-users"></i>
                Daftar User
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Kelola data pengguna dan hak akses sistem.
            </div>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-submit">
            <i class="fas fa-user-plus"></i>
            Tambah User
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:30%;">Nama</th>
                    <th style="width:35%;">Email</th>
                    <th style="width:15%; text-align:center;">Role</th>
                    <th style="width:20%; text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($users as $u)
                <tr>
                    <td>
                        <div class="user-name">{{ $u->name }}</div>
                    </td>
                    <td>
                        <div class="user-email">{{ $u->email }}</div>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge-role {{ $u->role }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                        <a href="{{ route('users.edit', $u->id) }}" class="btn-mini btn-edit">
                            <i class="fas fa-pen"></i> Edit
                        </a>

                        <form action="{{ route('users.destroy', $u->id) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-mini btn-delete">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">
                        Belum ada user.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
    body{
        font-family:'Inter', -apple-system, BlinkMacSystemFont,'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        letter-spacing:-0.1px;
    }

    /* Card */
    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
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
        color:#5a4a2a;
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
        color:#92400e;
        opacity:.85;
        font-weight:600;
    }

    /* Alerts */
    .alert-success{
        background:#dcfce7;
        border:1px solid #22c55e;
        color:#166534;
        padding:12px 14px;
        border-radius:12px;
        margin-bottom:14px;
        max-width:1800px;
        font-weight:700;
    }

    .alert-danger{
        background:#fee2e2;
        border:1px solid #ef4444;
        color:#991b1b;
        padding:12px 14px;
        border-radius:12px;
        margin-bottom:14px;
        max-width:1800px;
        font-weight:700;
    }

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
    }

    .table thead{
        background:#fff9c4;
    }

    .table th, .table td{
        padding:14px 14px;
        border-bottom:1px solid #fde68a;
        text-align:left;
        vertical-align:middle;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff9c4; }

    .empty{
        text-align:center;
        padding:22px;
        color:#8b7355;
        font-weight:700;
        background:#fffbeb;
    }

    .user-name{
        font-weight:800;
        color:#78350f;
    }

    .user-email{
        font-size:13px;
        color:#92400e;
        font-weight:600;
    }

    /* Role badge */
    .badge-role{
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        display:inline-block;
        text-transform:capitalize;
    }

    .badge-role.admin{
        background:#fee2e2;
        color:#991b1b;
        border:1px solid #fecaca;
    }

    .badge-role.pakar{
        background:#e0f2fe;
        color:#075985;
        border:1px solid #bae6fd;
    }

    .badge-role.user{
        background:#dcfce7;
        color:#166534;
        border:1px solid #bbf7d0;
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
    }

    .btn-edit{
        background:#fff9c4;
        color:#78350f;
        border:1px solid #fde68a;
    }

    .btn-delete{
        background:#fee2e2;
        color:#991b1b;
        border:1px solid #fecaca;
    }

    .btn-mini:hover{
        transform: translateY(-1px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .btn{ width:100%; justify-content:center; }
        .card-top{ flex-direction:column; align-items:stretch; }
    }
</style>

@endsection
