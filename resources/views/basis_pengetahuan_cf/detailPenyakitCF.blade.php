{{-- resources/views/basis_pengetahuan_cf/detailPenyakitCF.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Basis CF')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Detail Basis Pengetahuan CF</h1>
    <p class="page-subtitle">
        Penyakit: <b>{{ $penyakit->kode_penyakit }} - {{ $penyakit->nama_penyakit }}</b>
    </p>
</div>

{{-- Flash message --}}
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
                <i class="fas fa-list-check"></i>
                Daftar Gejala & Bobot CF
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Data relasi gejala untuk penyakit terpilih (beserta bobot CF).
            </div>
        </div>

        <a href="{{ route('basis_pengetahuan_cf.index') }}" class="btn btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    @php
        /**
         * Controller sebaiknya kirim:
         * $usedBasisCFIds = [..id basis_pengetahuan_cf yang tidak boleh dihapus..]
         * contoh: karena sudah dipakai di diagnosa/riwayat/dll
         */
        $usedBasisCFIds = $usedBasisCFIds ?? [];
    @endphp

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:140px; text-align:center;">Kode Gejala</th>
                    <th>Nama Gejala</th>
                    <th style="width:120px; text-align:center;">Bobot CF</th>
                    <th style="width:220px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rules as $i => $row)
                @php
                    $isUsed = in_array($row->id, $usedBasisCFIds, true);
                @endphp

                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td style="text-align:center;">{{ $row->gejala->kode_gejala }}</td>
                    <td>{{ $row->gejala->nama_gejala }}</td>
                    <td style="text-align:center;">
                        <span class="badge">{{ $row->cf_value }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div class="action-group">
                            <a href="{{ route('basis_pengetahuan_cf.edit', $row->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- ✅ tombol "Hapus" + modal custom (sama seperti penyakit/index) --}}
                            <button type="button"
                                    class="btn-mini btn-delete {{ $isUsed ? 'btn-disabled' : '' }}"
                                    onclick="openDeleteModal({{ $row->id }}, {{ $isUsed ? 'true' : 'false' }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            {{-- form delete hidden (disubmit lewat JS) --}}
                            <form id="delete-form-{{ $row->id }}"
                                  action="{{ route('basis_pengetahuan_cf.destroy', $row->id) }}"
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
                    <td colspan="5" class="empty">
                        Belum ada gejala untuk penyakit ini.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ===================== MODAL CUSTOM (SAMA SEPERTI PENYAKIT) ===================== --}}

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <h3 id="deleteConfirmTitle">Konfirmasi Penghapusan</h3>
        <p>Apakah Anda yakin ingin menghapus data basis CF ini?</p>

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
        <p>Data ini tidak bisa dihapus karena sudah digunakan di basis pengetahuan / diagnosa.</p>

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
