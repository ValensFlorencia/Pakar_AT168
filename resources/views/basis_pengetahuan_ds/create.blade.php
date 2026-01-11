@extends('layouts.app')

@section('title', 'Tambah Basis DS')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Tambah Basis Pengetahuan DS</h1>
    <p class="page-subtitle">Pilih penyakit, lalu tambahkan gejala dan bobot Dempster Shafer</p>
</div>

<div class="form-card">

    {{-- ALERT VALIDASI (tidak mengubah fungsi) --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ✅ hanya tambah id + novalidate (tampilan tidak berubah) --}}
    <form id="form-basis-ds" action="{{ route('basis_pengetahuan_ds.store') }}" method="POST" novalidate>
        @csrf

        {{-- Penyakit --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit <span class="required">*</span>
            </label>

            <select name="penyakit_id" id="penyakit-select" class="form-input form-select" required>
                <option value="">-- Pilih Penyakit --</option>
                @foreach($penyakits as $p)
                    <option value="{{ $p->id }}">{{ $p->kode_penyakit }} - {{ $p->nama_penyakit }}</option>
                @endforeach
            </select>

            <div class="input-hint">
                <i class="fas fa-info-circle"></i>
                Pilih penyakit dulu, agar gejala yang sudah pernah dipakai otomatis tidak bisa dipilih lagi
            </div>
        </div>

        {{-- Gejala Section --}}
        <div class="gejala-section">
            <div class="section-label">
                <i class="fas fa-list-check"></i>
                Gejala & Bobot Dempster Shafer
            </div>

            <div id="gejala-wrapper">
                <div class="gejala-row">
                    <select name="gejalas[0][gejala_id]" class="form-input gejala-select" required>
                        <option value="">-- Pilih Gejala --</option>
                        @foreach($gejalas as $g)
                            <option value="{{ $g->id }}">{{ $g->kode_gejala }} - {{ $g->nama_gejala }}</option>
                        @endforeach
                    </select>

                    <select name="gejalas[0][ds_value]" class="form-input ds-select" required>
                        <option value="">-- Bobot DS --</option>
                        <option value="0.2">0.2 - Sedikit Yakin</option>
                        <option value="0.4">0.4 - Cukup Yakin</option>
                        <option value="0.6">0.6 - Hampir Yakin</option>
                        <option value="0.8">0.8 - Yakin</option>
                        <option value="1">1 - Sangat Yakin</option>
                    </select>

                    <button type="button" class="btn-delete" onclick="hapusBaris(this)" title="Hapus baris">✕</button>
                </div>
            </div>

            <button type="button" class="btn-add" onclick="tambahGejala()">
                <i class="fas fa-plus"></i> Tambah Gejala
            </button>

            <div class="input-hint" style="margin-top:14px;">
                <i class="fas fa-lightbulb"></i>
                Tambahkan beberapa gejala untuk 1 penyakit (gejala tidak boleh duplikat)
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Data
            </button>

            <a href="{{ route('basis_pengetahuan_ds.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </form>
</div>

{{-- ============ CSS FORM (tetap seperti kamu, tidak diubah) ============ --}}
<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-icon { font-size: 20px; margin-top: 2px; }
    .alert-content { flex: 1; }
    .alert-content strong { display: block; margin-bottom: 8px; font-weight: 600; }
    .alert-content ul { margin: 0; padding-left: 20px; }
    .alert-content li { margin-bottom: 4px; font-size: 14px; }

    .form-group { margin-bottom: 28px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color:#000;
        margin-bottom: 10px;
    }

    .form-label i { color: #f59e0b; font-size: 16px; }

    .required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 2px;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #ffffff;
        border-radius: 10px;
        font-size: 14px;
        color:#000;
        transition: all 0.2s ease;
        font-family: inherit;
        outline: none;
    }

    .form-input:focus {
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color:#000;
        margin-top: 8px;
        opacity: 0.8;
    }

    .input-hint i { font-size: 12px; }

    .gejala-section {
        background: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 22px;
        margin-top: 10px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: #000;
        margin-bottom: 16px;
    }

    .section-label i { color: #f59e0b; }

    #gejala-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .gejala-row {
        display: flex;
        gap: 12px;
        align-items: center;
        background: transparent;
        padding: 14px;
        border-radius: 12px;
    }

    .gejala-select { flex: 2; }
    .ds-select { flex: 1; }

    .btn-delete {
        background: #ffffff;
        border: 2px solid #fecaca;
        border-radius: 10px;
        padding: 10px 14px;
        color: #b91c1c;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        transform: translateY(-1px);
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding: 12px 18px;
        border-radius: 10px;
        background: #ffffff;
        appearance: none;
        -webkit-appearance: none;
        color: #000;
        border: 2px solid #fed7aa;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        background: #fff7ed;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.18);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 1px solid #fde68a;
    }

    .btn {
        padding: 13px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn i { font-size: 14px; }

    .btn-submit {
        background: #f59e0b;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #000;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .form-card { padding: 24px; }
        .gejala-row { flex-direction: column; align-items: stretch; }
        .btn-delete { width: 100%; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    // ✅ dari controller DS: $existingMap (penyakit_id => [gejala_id...])
    const existingMap = @json($existingMap ?? []);

    function swalWarn(msg) {
        return Swal.fire({
            icon: 'warning',
            title: 'Lengkapi Data',
            text: msg,
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: { popup: 'swal-yellow', confirmButton: 'btn-yellow' }
        });
    }

    function swalCantDelete() {
        return Swal.fire({
            icon: 'warning',
            title: 'Tidak Bisa Dihapus',
            text: 'Minimal harus ada 1 gejala!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: { popup: 'swal-yellow', confirmButton: 'btn-yellow' }
        });
    }

    // ✅ DISABLE LOGIC (DB + anti duplikat antar row)
    function refreshGejalaOptions() {
        const penyakitId = document.getElementById('penyakit-select')?.value ?? "";

        const alreadyInDb = (penyakitId && existingMap[penyakitId])
            ? existingMap[penyakitId].map(String)
            : [];

        const selects = document.querySelectorAll('.gejala-select');

        const selectedValues = Array.from(selects)
            .map(s => s.value)
            .filter(v => v !== "")
            .map(String);

        selects.forEach(select => {
            const currentValue = String(select.value || "");

            Array.from(select.options).forEach(option => {
                if (option.value === "") return;

                const val = String(option.value);

                const disabledByDb  = alreadyInDb.includes(val);
                const disabledByRow = selectedValues.includes(val) && val !== currentValue;

                if (disabledByDb || disabledByRow) {
                    option.disabled = true;
                    option.style.color = "#999";
                } else {
                    option.disabled = false;
                    option.style.color = "#000";
                }
            });

            // kalau sedang terpilih tapi ternyata sudah ada di DB → reset
            if (currentValue && alreadyInDb.includes(currentValue)) {
                select.value = "";
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-basis-ds');

        // ✅ saat awal load
        refreshGejalaOptions();

        // ✅ validasi submit -> SweetAlert
        form.addEventListener('submit', function(e) {
            const penyakit = document.getElementById('penyakit-select')?.value ?? '';
            if (!penyakit) {
                e.preventDefault();
                swalWarn('Lengkapi data yang belum lengkap!');
                document.getElementById('penyakit-select')?.focus();
                return;
            }

            const rows = form.querySelectorAll('.gejala-row');
            for (let row of rows) {
                const gejala = row.querySelector('.gejala-select')?.value ?? '';
                const ds     = row.querySelector('.ds-select')?.value ?? '';

                if (!gejala) {
                    e.preventDefault();
                    swalWarn('Lengkapi data yang belum lengkap!');
                    row.querySelector('.gejala-select')?.focus();
                    return;
                }

                if (!ds) {
                    e.preventDefault();
                    swalWarn('Lengkapi data yang belum lengkap!');
                    row.querySelector('.ds-select')?.focus();
                    return;
                }
            }
        });

        // ✅ refresh saat penyakit / gejala berubah
        document.addEventListener('change', function(e) {
            if (e.target.id === 'penyakit-select' || e.target.classList.contains('gejala-select')) {
                refreshGejalaOptions();
            }
        });
    });

    let index = 1;

    function tambahGejala() {
        const wrapper = document.getElementById('gejala-wrapper');

        const html = `
        <div class="gejala-row">
            <select name="gejalas[${index}][gejala_id]" class="form-input gejala-select" required>
                <option value="">-- Pilih Gejala --</option>
                @foreach($gejalas as $g)
                    <option value="{{ $g->id }}">{{ $g->kode_gejala }} - {{ $g->nama_gejala }}</option>
                @endforeach
            </select>

            <select name="gejalas[${index}][ds_value]" class="form-input ds-select" required>
                <option value="">-- Bobot DS --</option>
                <option value="0.2">0.2 - Sedikit Yakin</option>
                <option value="0.4">0.4 - Cukup Yakin</option>
                <option value="0.6">0.6 - Hampir Yakin</option>
                <option value="0.8">0.8 - Yakin</option>
                <option value="1">1 - Sangat Yakin</option>
            </select>

            <button type="button" class="btn-delete" onclick="hapusBaris(this)">✕</button>
        </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        index++;

        // ✅ setelah tambah row, refresh disable option
        refreshGejalaOptions();
    }

    function hapusBaris(btn) {
        const wrapper = document.getElementById('gejala-wrapper');
        const rows = wrapper.querySelectorAll('.gejala-row');

        if (rows.length > 1) {
            btn.closest('.gejala-row').remove();
            // ✅ setelah hapus row, refresh disable option
            refreshGejalaOptions();
        } else {
            swalCantDelete();
        }
    }
</script>

@endsection
