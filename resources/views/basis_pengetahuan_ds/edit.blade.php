@extends('layouts.app')

@section('title', 'Edit Basis Pengetahuan DS')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Basis Pengetahuan DS</h1>
    <p class="page-subtitle">Perbarui bobot Dempster Shafer untuk gejala pada penyakit tertentu</p>
</div>

<div class="form-card">

    {{-- ALERT VALIDASI --}}
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

    <form action="{{ route('basis_pengetahuan_ds.update', $basisDS->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- NAMA PENYAKIT (DISABLED) --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit
            </label>

            <select disabled class="form-input input-readonly">
                <option>
                    {{ $basisDS->penyakit->kode_penyakit }} - {{ $basisDS->penyakit->nama_penyakit }}
                </option>
            </select>

            {{-- hidden agar tetap terkirim (fungsi tetap) --}}
            <input type="hidden" name="penyakit_id" value="{{ $basisDS->penyakit_id }}">

            <div class="input-hint">
                <i class="fas fa-lock"></i>
                Penyakit tidak dapat diubah
            </div>
        </div>

        {{-- SECTION GEJALA & DS --}}
        <div class="gejala-section">

            <div class="section-label">
                <i class="fas fa-list-check"></i>
                Gejala & Bobot Dempster Shafer
            </div>

            <div class="gejala-row">

                {{-- GEJALA (DISABLED) --}}
                <select disabled class="form-input gejala-select">
                    <option>
                        {{ $basisDS->gejala->kode_gejala }} - {{ $basisDS->gejala->nama_gejala }}
                    </option>
                </select>

                <input type="hidden" name="gejala_id" value="{{ $basisDS->gejala_id }}">

                @php
                    $currentDS = (string)((float)$basisDS->ds_value);
                    $dsOptions = ['0','0.2','0.4','0.6','0.8','1'];
                @endphp

                {{-- BOBOT DS --}}
                <select name="ds_value" required class="form-input ds-select">
                    <option value="">-- Bobot DS --</option>
                    @foreach($dsOptions as $v)
                        <option value="{{ $v }}"
                            {{ old('ds_value', $currentDS) === (string)((float)$v) ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="input-hint" style="margin-top:14px;">
                <i class="fas fa-lightbulb"></i>
                Ubah bobot DS sesuai keyakinan pakar (0 sampai 1)
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Perubahan
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

    /* Alert Styling */
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
        color: #991b1b; /* tetap merah (error) */
    }
    .alert-icon { font-size: 20px; margin-top: 2px; }
    .alert-content { flex: 1; }
    .alert-content strong { display: block; margin-bottom: 8px; font-weight: 600; }
    .alert-content ul { margin: 0; padding-left: 20px; }
    .alert-content li { margin-bottom: 4px; font-size: 14px; }

    /* Form Group */
    .form-group { margin-bottom: 28px; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #111827; /* hitam */
        margin-bottom: 10px;
    }
    .form-label i { color: #f59e0b; font-size: 16px; }

    /* Input */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #fffbeb;
        border-radius: 10px;
        font-size: 14px;
        color: #111827; /* hitam */
        transition: all 0.2s ease;
        font-family: inherit;
        outline: none;
    }
    .form-input:focus {
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .input-readonly {
        background: #fef3c7;
        font-weight: 600;
        cursor: not-allowed;
        color: #111827; /* hitam */
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #111827; /* hitam */
        margin-top: 8px;
        opacity: 0.7;
    }
    .input-hint i { font-size: 12px; }

    /* Section */
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
        font-weight: 800;
        color: #111827; /* hitam */
        margin-bottom: 16px;
    }
    .section-label i { color: #f59e0b; }

    /* ✅ Samain dengan CF: layout row-wrap/col look */
    .gejala-row{
        display:flex;
        gap:18px;
        align-items:flex-start;
        flex-wrap:wrap;
        background: transparent;  /* beda dari sebelumnya */
        padding: 0;               /* samain CF */
        border: 0;                /* samain CF */
        border-radius: 0;
        box-shadow: none;
    }

    .gejala-select{
        flex: 1;
        min-width: 280px;
    }

    .ds-select{
        flex: 1;
        min-width: 280px;
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
        color: #ffffff; /* tetap putih */
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #111827; /* hitam */
    }
    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .page-title{
        color:#111827;
    }

    .page-subtitle{
        color:#374151;
    }

    @media (max-width: 768px) {
        .form-card { padding: 24px; }
        .gejala-row{ flex-direction:column; align-items:stretch; }
        .gejala-select, .ds-select{ min-width:unset; width:100%; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

@endsection
