@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="user-edit-page">

    <div class="page-header" style="margin-bottom:30px;">
        <h1 class="page-title">Edit Pengguna</h1>
        <p class="page-subtitle">Ubah data pengguna, role, dan (opsional) password.</p>
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
                    Form Edit Pengguna
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

        {{-- ✅ hanya tambah id + novalidate (fungsi lain tidak diubah) --}}
        <form id="form-edit-user" method="POST" action="{{ route('users.update', $user->id) }}" novalidate>
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
                    Perbarui
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>

    </div>

</div>

{{-- =======================
     SWEETALERT2
======================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ CSS SweetAlert kuning kecil (hanya modal, tidak ubah tampilan form) --}}
<style>
.swal2-popup.swal-yellow {
    width: 360px !important;
    padding: 18px 20px !important;
    border-radius: 14px;
    border: 2px solid #f6c453;
    box-shadow: 0 12px 35px rgba(0,0,0,.18);
}
.swal2-popup.swal-yellow .swal2-icon {
    width: 56px !important;
    height: 56px !important;
    margin: 12px auto 8px !important;
}
.swal2-popup.swal-yellow .swal2-icon.swal2-warning {
    border-color: #f6c453 !important;
    color: #f6c453 !important;
}
.swal2-popup.swal-yellow .swal2-title {
    font-size: 18px !important;
    font-weight: 700;
    margin: 6px 0 4px !important;
    color: #3a2a00;
}
.swal2-popup.swal-yellow .swal2-html-container {
    font-size: 14px !important;
    line-height: 1.4;
    margin: 4px 0 10px !important;
    color: #5a4300;
}
.swal2-popup.swal-yellow .swal2-confirm.btn-yellow {
    font-size: 13px !important;
    padding: 8px 16px !important;
    border-radius: 10px !important;
    background: #f6c453 !important;
    color: #3a2a00 !important;
    border: 0 !important;
    font-weight: 700 !important;
    box-shadow: 0 6px 16px rgba(246,196,83,.35);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-edit-user');
    if (!form) return;

    const swalWarn = (msg) => Swal.fire({
        icon: 'warning',
        title: 'Lengkapi Data',
        text: msg,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: { popup: 'swal-yellow', confirmButton: 'btn-yellow' }
    });

    form.addEventListener('submit', function(e) {
        const name  = form.querySelector('input[name="name"]')?.value?.trim() ?? '';
        const email = form.querySelector('input[name="email"]')?.value?.trim() ?? '';
        const role  = form.querySelector('select[name="role"]')?.value ?? '';

        const pass  = form.querySelector('input[name="password"]')?.value ?? '';
        const pass2 = form.querySelector('input[name="password_confirmation"]')?.value ?? '';

        // ✅ wajib: name
        if (!name) {
            e.preventDefault();
            swalWarn('Lengkapi data yang belum lengkap!');
            form.querySelector('input[name="name"]')?.focus();
            return;
        }

        // ✅ wajib: email
        if (!email) {
            e.preventDefault();
            swalWarn('Lengkapi data yang belum lengkap!');
            form.querySelector('input[name="email"]')?.focus();
            return;
        }

        // ✅ wajib: role
        if (!role) {
            e.preventDefault();
            swalWarn('Lengkapi data yang belum lengkap!');
            form.querySelector('select[name="role"]')?.focus();
            return;
        }

        // ✅ password opsional:
        // jika password diisi, konfirmasi wajib + harus sama
        if (pass.trim() !== '') {
            if (pass2.trim() === '') {
                e.preventDefault();
                swalWarn('Konfirmasi password masih kosong!');
                form.querySelector('input[name="password_confirmation"]')?.focus();
                return;
            }

            if (pass !== pass2) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Tidak Sama',
                    text: 'Password dan konfirmasi password harus sama.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: { popup: 'swal-yellow', confirmButton: 'btn-yellow' }
                });
                form.querySelector('input[name="password_confirmation"]')?.focus();
                return;
            }
        }
    });
});
</script>

@endsection
