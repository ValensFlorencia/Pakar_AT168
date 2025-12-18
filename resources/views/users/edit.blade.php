@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="user-edit-page">

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
                    Password bersifat opsional, kosongkan jika tidak ingin mengubah.
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
                        <option value="" disabled class="placeholder">Pilih role...</option>
                        <option value="pakar" {{ old('role', $user->role)=='pakar' ? 'selected' : '' }}>Pakar</option>
                        <option value="peternak" {{ old('role', $user->role)=='peternak' ? 'selected' : '' }}>Peternak</option>
                        <option value="pemilik" {{ old('role', $user->role)=='pemilik' ? 'selected' : '' }}>Pemilik</option>
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

</div>

<style>
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
</style>

@endsection
