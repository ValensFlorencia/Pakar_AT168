@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

<div class="user-create-page">

    <div class="page-header" style="margin-bottom:30px;">
        <h1 class="page-title">Tambah User</h1>
        <p class="page-subtitle">Buat akun baru dan tentukan role.</p>
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
                    <i class="fas fa-user-plus"></i>
                    Form Tambah User
                </h2>
                <div class="card-subtitle">
                    <i class="fas fa-info-circle"></i>
                    Isi data akun dengan benar. Password minimal sesuai aturan sistem.
                </div>
            </div>

            <a href="{{ route('users.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- autocomplete off di form (tetap bisa diabaikan Chrome, makanya ada readonly-trick di bawah) --}}
        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
            @csrf

            <div class="grid-2">
                <div class="field">
                    <label class="label">Nama</label>
                    <input
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="input"
                        placeholder="Masukkan nama"
                        autocomplete="off"
                    >
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="input"
                        placeholder="Masukkan email"
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="false"
                        readonly
                        onfocus="this.removeAttribute('readonly');"
                    >
                </div>

                <div class="field">
                    <label class="label">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="input"
                        placeholder="Masukkan password"
                        autocomplete="new-password"
                        readonly
                        onfocus="this.removeAttribute('readonly');"
                    >
                </div>

                <div class="field">
                    <label class="label">Konfirmasi Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="input"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        readonly
                        onfocus="this.removeAttribute('readonly');"
                    >
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label class="label">Role</label>
                    <select name="role" required class="select">
                        <option value="" disabled selected class="placeholder">Pilih role...</option>
                        <option value="pakar" {{ old('role')=='pakar' ? 'selected' : '' }}>Pakar</option>
                        <option value="peternak" {{ old('role')=='peternak' ? 'selected' : '' }}>Peternak</option>
                        <option value="pemilik" {{ old('role')=='pemilik' ? 'selected' : '' }}>Pemilik</option>
                    </select>
                </div>
            </div>

            <div class="btn-actions">
                <button class="btn btn-submit" type="submit">
                    <i class="fas fa-floppy-disk"></i>
                    Simpan
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
</style>

@endsection
