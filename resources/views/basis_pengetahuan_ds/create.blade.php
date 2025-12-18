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

    {{-- ⛔ HTML & SCRIPT DI BAWAH TIDAK DIUBAH (fungsi tetap) --}}
    <form action="{{ route('basis_pengetahuan_ds.store') }}" method="POST">
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
                        <option value="0">0 - Tidak</option>
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

<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #fde68a;
        max-width: 1800px;
    }

    /* Alert */
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

    /* Form */
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

    /* ✅ Samain CF: input polos putih */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #ffffff;  /* putih */
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

    /* Section gejala */
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

    /* ✅ Samain CF: row gejala tanpa background */
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

    /* ✅ Samain CF: delete putih */
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

    /* ✅ Samain CF: tombol add putih */
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

    /* Actions */
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


<script>
    // ✅ dari controller DS: $existingMap (penyakit_id => [gejala_id...])
    const existingMap = @json($existingMap ?? []);

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

            if (currentValue && alreadyInDb.includes(currentValue)) {
                select.value = "";
            }
        });
    }

    document.addEventListener('DOMContentLoaded', refreshGejalaOptions);

    document.addEventListener('change', function(e) {
        if (e.target.id === 'penyakit-select' || e.target.classList.contains('gejala-select')) {
            refreshGejalaOptions();
        }
    });

    let index = 1;

    function tambahGejala() {
        let wrapper = document.getElementById('gejala-wrapper');

        let html = `
        <div class="gejala-row">
            <select name="gejalas[${index}][gejala_id]" class="form-input gejala-select" required>
                <option value="">-- Pilih Gejala --</option>
                @foreach($gejalas as $g)
                    <option value="{{ $g->id }}">{{ $g->kode_gejala }} - {{ $g->nama_gejala }}</option>
                @endforeach
            </select>

            <select name="gejalas[${index}][ds_value]" class="form-input ds-select" required>
                <option value="">-- Bobot DS --</option>
                <option value="0">0 - Tidak</option>
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

        refreshGejalaOptions();
    }

    function hapusBaris(btn) {
        let wrapper = document.getElementById('gejala-wrapper');
        let rows = wrapper.querySelectorAll('.gejala-row');

        if (rows.length > 1) {
            btn.closest('.gejala-row').remove();
            refreshGejalaOptions();
        } else {
            alert('Minimal harus ada 1 gejala!');
        }
    }
</script>

@endsection
