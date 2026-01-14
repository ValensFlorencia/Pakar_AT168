{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Users')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Pengguna</h1>
    <p class="page-subtitle">Kelola akun dan role.</p>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<div class="form-card" style="max-width:1800px;">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-users"></i>
                Daftar Pengguna
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Kelola data pengguna dan hak akses sistem.
            </div>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-submit">
            <i class="fas fa-user-plus"></i>
            Tambah Pengguna
        </a>
    </div>

    @php
        /**
         * Controller harus kirim:
         * $usedUserIds = [..id user yang sudah pernah diagnosa..]
         */
        $usedUserIds = $usedUserIds ?? [];
        $authId = auth()->id(); // ✅ id user yang sedang login
    @endphp

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
                @php
                    $isUsed = in_array($u->id, $usedUserIds, true); // ✅ pernah diagnosa?
                    $isSelf = ($authId === $u->id);                 // ✅ akun sedang login?
                @endphp

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
                        <div class="action-group" style="justify-content:center;">

                            <a href="{{ route('users.edit', $u->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- ✅ tombol Hapus pakai modal custom --}}
                            <button type="button"
                                    class="btn-mini btn-delete {{ ($isUsed || $isSelf) ? 'btn-disabled' : '' }}"
                                    onclick="openDeleteModal({{ $u->id }}, {{ $isUsed ? 'true' : 'false' }}, {{ $isSelf ? 'true' : 'false' }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            {{-- form delete hidden, disubmit lewat JS --}}
                            <form id="delete-form-{{ $u->id }}"
                                  action="{{ route('users.destroy', $u->id) }}"
                                  method="POST"
                                  style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">
                        Belum ada pengguna.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ===================== MODAL CUSTOM ===================== --}}

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <h3 id="deleteConfirmTitle">Konfirmasi Penghapusan</h3>
        <p>Apakah Anda yakin ingin menghapus pengguna ini?</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal Validasi Tidak Bisa Hapus (pernah diagnosa) --}}
<div id="cannotDeleteModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="cannotDeleteTitle">
        <h3 id="cannotDeleteTitle">Penghapusan Ditolak</h3>
        <p>Pengguna ini tidak bisa dihapus karena sudah pernah melakukan diagnosa.</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-submit" onclick="closeCannotDeleteModal()">OK</button>
        </div>
    </div>
</div>

{{-- Modal Tidak Bisa Hapus Akun Sendiri --}}
<div id="cannotDeleteSelfModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="cannotDeleteSelfTitle">
        <h3 id="cannotDeleteSelfTitle">Penghapusan Ditolak</h3>
        <p>Tidak bisa menghapus akun yang sedang login.</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-submit" onclick="closeCannotDeleteSelfModal()">OK</button>
        </div>
    </div>
</div>

<script>
    // Simpan target delete
    let deleteId = null;
    let deleteIsUsed = false;
    let deleteIsSelf = false;

    function openDeleteModal(id, isUsed, isSelf) {
        deleteId = id;
        deleteIsUsed = isUsed;
        deleteIsSelf = isSelf;

        // ✅ PRIORITAS 1: tidak boleh hapus akun sendiri
        if (deleteIsSelf) {
            const modal = document.getElementById('cannotDeleteSelfModal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            return;
        }

        // ✅ PRIORITAS 2: tidak boleh hapus user yang pernah diagnosa
        if (deleteIsUsed) {
            const modal = document.getElementById('cannotDeleteModal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            return;
        }

        // ✅ Kalau aman, baru tampilkan konfirmasi
        const modal = document.getElementById('deleteConfirmModal');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    function confirmDelete() {
        closeDeleteModal();
        const form = document.getElementById('delete-form-' + deleteId);
        if (form) form.submit();
    }

    function closeCannotDeleteModal() {
        const modal = document.getElementById('cannotDeleteModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    function closeCannotDeleteSelfModal() {
        const modal = document.getElementById('cannotDeleteSelfModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    // klik background untuk menutup modal
    document.addEventListener('click', function (e) {
        const confirmModal = document.getElementById('deleteConfirmModal');
        const cannotModal  = document.getElementById('cannotDeleteModal');
        const selfModal    = document.getElementById('cannotDeleteSelfModal');

        if (e.target === confirmModal) closeDeleteModal();
        if (e.target === cannotModal) closeCannotDeleteModal();
        if (e.target === selfModal) closeCannotDeleteSelfModal();
    });

    // ESC untuk menutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeCannotDeleteModal();
            closeCannotDeleteSelfModal();
        }
    });
</script>

@endsection
