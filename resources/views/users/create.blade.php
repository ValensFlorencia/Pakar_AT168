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
@endsection
