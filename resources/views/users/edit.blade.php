@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit User</h1>
    <p class="page-subtitle">Ubah data user, role, dan (opsional) password.</p>
</div>

@if ($errors->any())
    <div class="alert-danger">
        <div class="alert-icon">⚠️</div>
        <div>
            <div style="font-weight:900;margin-bottom:6px;">Terjadi kesalahan:</div>
            <ul style="margin:0 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li style="margin:4px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="form-card" style="max-width:1800px;">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-user-pen"></i>
                Form Edit User
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Password bersifat opsional—kosongkan jika tidak ingin mengubah.
            </div>
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="grid-2">
            <div class="field">
                <label class="label">Nama</label>
                <input name="name" value="{{ old('name',$user->name) }}" required class="input"
                       placeholder="Nama lengkap">
            </div>

            <div class="field">
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email',$user->email) }}" required class="input"
                       placeholder="contoh@email.com">
            </div>

            <div class="field" style="grid-column: span 2;">
                <label class="label">Role</label>
                <select name="role" required class="select">
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ old('role',$user->role) === $r ? 'selected' : '' }}>
                            {{ ucfirst($r) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label class="label">Password Baru (Opsional)</label>
                <input type="password" name="password" class="input" placeholder="Kosongkan jika tidak diubah">
                <div class="help-text">Kosongkan jika tidak ingin mengubah password.</div>
            </div>

            <div class="field">
                <label class="label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="input" placeholder="Ulangi password baru">
            </div>
        </div>

        <div class="btn-actions">
            <button class="btn btn-submit" type="submit">
                <i class="fas fa-floppy-disk"></i>
                Update
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>

</div>

<style>
    body{
        font-family:'Inter', -apple-system, BlinkMacSystemFont,'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        letter-spacing:-0.1px;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
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
    .card-subtitle i{ font-size:12px; }

    .grid-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .label{
        display:block;
        font-size:13px;
        font-weight:900;
        color:#92400e;
        margin-bottom:8px;
    }

    .input, .select{
        width:100%;
        padding:12px 14px;
        border:1.8px solid #fde68a;
        border-radius:14px;
        background:#fff;
        color:#78350f;
        font-weight:700;
        outline:none;
        transition:.15s ease;
        font-size:14px;
    }

    .input:focus, .select:focus{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    .help-text{
        margin-top:8px;
        font-size:12px;
        color:#6b7280;
        font-weight:600;
    }

    .btn-actions{
        display:flex;
        gap:10px;
        margin-top:18px;
        padding-top:18px;
        border-top:1px solid #fde68a;
        justify-content:flex-end;
        flex-wrap:wrap;
    }

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

    .btn-outline{
        background:#ffffff;
        color:#78350f;
        border:2px solid #fde68a;
    }

    .btn-outline:hover{
        transform: translateY(-1px);
        border-color:#f59e0b;
    }

    .alert-danger{
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
    .alert-icon{ line-height:1; }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .grid-2{ grid-template-columns:1fr; }
        .btn{ width:100%; justify-content:center; }
        .btn-actions{ justify-content:stretch; }
    }
</style>

@endsection
``
