{{-- resources/views/penyakit/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penyakit')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Penyakit Ayam Petelur</h1>
    <p class="page-subtitle">
        Kelola daftar penyakit yang digunakan pada proses diagnosa sistem pakar.
    </p>
</div>

@if (session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<div class="form-card">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-virus"></i>
                Daftar Penyakit
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Data penyakit yang digunakan dalam sistem pakar.
            </div>
        </div>

        <a href="{{ route('penyakit.create') }}" class="btn btn-submit">
            <i class="fas fa-plus"></i>
            Tambah Penyakit
        </a>
    </div>

    @php
        // Controller harus kirim: $usedPenyakitIds = [.. id penyakit yang sudah dipakai ..]
        $usedPenyakitIds = $usedPenyakitIds ?? [];
    @endphp

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:100px;">Kode</th>
                    <th style="width:220px;">Nama Penyakit</th>
                    <th>Deskripsi</th>
                    <th>Solusi</th>
                    <th style="width:220px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($penyakits as $index => $penyakit)
                @php
                    $isUsed = in_array($penyakit->id, $usedPenyakitIds, true);
                @endphp

                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="font-weight:700;">{{ $penyakit->kode_penyakit }}</td>
                    <td>{{ $penyakit->nama_penyakit }}</td>
                    <td>{{ $penyakit->deskripsi }}</td>
                    <td>{{ $penyakit->solusi }}</td>
                    <td style="text-align:center;">
                        <div class="action-group">

                            <a href="{{ route('penyakit.edit', $penyakit->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- ✅ tombol selalu "Hapus" + pakai modal custom --}}
                            <button type="button"
                                    class="btn-mini btn-delete {{ $isUsed ? 'btn-disabled' : '' }}"
                                    onclick="openDeleteModal({{ $penyakit->id }}, {{ $isUsed ? 'true' : 'false' }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            {{-- form delete disembunyikan, disubmit lewat JS --}}
                            <form id="delete-form-{{ $penyakit->id }}"
                                  action="{{ route('penyakit.destroy', $penyakit->id) }}"
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
                    <td colspan="6" class="empty">
                        Belum ada data penyakit.
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
        <p>Apakah Anda yakin ingin menghapus penyakit ini?</p>

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
        <p>Penyakit ini tidak bisa dihapus karena sudah digunakan di basis pengetahuan / diagnosa.</p>

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
        closeDeleteModal();

        // validasi: sudah dipakai?
        if (deleteIsUsed) {
            const modal = document.getElementById('cannotDeleteModal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            return;
        }

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
