{{-- resources/views/gejala/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Gejala Ayam Petelur</h1>
    <p class="page-subtitle">Kelola daftar gejala yang digunakan pada proses diagnosa sistem pakar.</p>
</div>

<div class="form-card">

    {{-- ALERT ERROR (untuk pesan dari controller) --}}
    @if(session('error'))
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-list-check"></i>
                Daftar Gejala
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Data gejala yang akan dipakai pada proses diagnosa.
            </div>
        </div>

        <a href="{{ route('gejala.create') }}" class="btn btn-submit">
            <i class="fas fa-plus"></i>
            Tambah Gejala
        </a>
    </div>

    @php
        // Controller idealnya kirim: $usedGejalaIds = [...id gejala yang dipakai di CF/DS...]
        // Ini biar view tidak error kalau variabel belum ada.
        $usedGejalaIds = $usedGejalaIds ?? [];
    @endphp

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:140px;">Kode</th>
                    <th>Nama Gejala</th>
                    <th style="width:260px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($gejalas as $index => $gejala)
                @php
                    $isUsed = in_array($gejala->id, $usedGejalaIds, true);
                @endphp

                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="font-weight:700;">{{ $gejala->kode_gejala }}</td>
                    <td>{{ $gejala->nama_gejala }}</td>
                    <td style="text-align:center;">
                        <div class="action-group">

                            <a href="{{ route('gejala.edit', $gejala->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- ✅ tombol selalu "Hapus" + pakai modal custom --}}
                            <button type="button"
                                    class="btn-mini btn-delete {{ $isUsed ? 'btn-disabled' : '' }}"
                                    onclick="openDeleteModal({{ $gejala->id }}, {{ $isUsed ? 'true' : 'false' }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            {{-- form delete disembunyikan, disubmit lewat JS --}}
                            <form id="delete-form-{{ $gejala->id }}"
                                  action="{{ route('gejala.destroy', $gejala->id) }}"
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
                        Belum ada data gejala.
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
        <p>Apakah Anda yakin ingin menghapus gejala ini?</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal Validasi Tidak Bisa Hapus --}}
<div id="cannotDeleteModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="cannotDeleteTitle">
        <h3 id="cannotDeleteTitle">Penghapusan Ditolak</h3>
        <p>Gejala ini tidak bisa dihapus karena sudah digunakan di basis pengetahuan / diagnosa.</p>

        <div class="modal-actions">
            <button type="button" class="btn btn-submit" onclick="closeCannotDeleteModal()">OK</button>
        </div>
    </div>
</div>

<script>
    // Simpan target delete
    let deleteId = null;
    let deleteIsUsed = false;

    function openDeleteModal(id, isUsed) {
        deleteId = id;
        deleteIsUsed = isUsed;

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
        // tutup modal konfirmasi
        closeDeleteModal();

        // validasi: sudah dipakai?
        if (deleteIsUsed) {
            const modal = document.getElementById('cannotDeleteModal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            return;
        }

        // submit form delete
        const form = document.getElementById('delete-form-' + deleteId);
        if (form) form.submit();
    }

    function closeCannotDeleteModal() {
        const modal = document.getElementById('cannotDeleteModal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    // klik background untuk menutup modal
    document.addEventListener('click', function (e) {
        const confirmModal = document.getElementById('deleteConfirmModal');
        const cannotModal  = document.getElementById('cannotDeleteModal');

        if (e.target === confirmModal) closeDeleteModal();
        if (e.target === cannotModal) closeCannotDeleteModal();
    });

    // ESC untuk menutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeCannotDeleteModal();
        }
    });
</script>

@endsection
