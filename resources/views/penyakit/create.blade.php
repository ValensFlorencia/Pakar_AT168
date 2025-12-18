{{-- resources/views/penyakit/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penyakit')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Tambah Penyakit Baru</h1>
    <p class="page-subtitle">Masukkan informasi penyakit ayam</p>
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

    <form action="{{ route('penyakit.store') }}" method="POST">
        @csrf

        {{-- Kode Penyakit --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-barcode"></i>
                Kode Penyakit
            </label>
            <input
                type="text"
                name="kode_penyakit"
                value="{{ $newCode ?? '' }}"
                readonly
                class="form-input input-readonly">
            <div class="input-hint">
                <i class="fas fa-info-circle"></i>
                Kode dibuat otomatis oleh sistem (P01, P02, dst)
            </div>
        </div>

        {{-- Nama Penyakit --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit
                <span class="required">*</span>
            </label>
            <input
                type="text"
                name="nama_penyakit"
                value="{{ old('nama_penyakit') }}"
                placeholder="Contoh: Newcastle Disease (ND)"
                required
                class="form-input">
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Masukkan nama penyakit sesuai istilah yang umum digunakan
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-align-left"></i>
                Deskripsi
                <span class="required">*</span>
            </label>
            <textarea
                name="deskripsi"
                rows="4"
                placeholder="Masukkan deskripsi penyakit"
                required
                class="form-input form-textarea">{{ old('deskripsi') }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Jelaskan ciri umum penyakit dan dampaknya pada ayam
            </div>
        </div>

        {{-- Solusi --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-notes-medical"></i>
                Solusi / Penanganan
                <span class="required">*</span>
            </label>
            <textarea
                name="solusi"
                rows="4"
                placeholder="Masukkan solusi / penanganan penyakit"
                required
                class="form-input form-textarea">{{ old('solusi') }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Tulis langkah penanganan yang disarankan (obat, isolasi, vaksin, dll)
            </div>
        </div>

        {{-- Tombol --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Data
            </button>

            <a href="{{ route('penyakit.index') }}" class="btn btn-cancel">
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
        color: #991b1b;
    }

    .alert-icon {
        font-size: 20px;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .alert-content ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-content li {
        margin-bottom: 4px;
        font-size: 14px;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 28px;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        color: #000;
        margin-bottom: 10px;
    }

    .form-label i {
        color: #f59e0b;
        font-size: 16px;
    }

    .required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 2px;
    }

    /* Form Input */
    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #fde68a;
        background: #fffbeb;
        border-radius: 10px;
        font-size: 14px;
        color: #000;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #f59e0b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .form-input::placeholder {
        color: #000;
        opacity: 0.6;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }

    .input-readonly {
        background: #fef3c7;
        font-weight: 600;
        cursor: not-allowed;
        color: #000;
    }

    .input-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #000;
        margin-top: 8px;
        opacity: 0.8;
    }

    .input-hint i {
        font-size: 12px;
    }

    /* Form Actions */
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

    .btn i {
        font-size: 14px;
    }

    .btn-submit {
        background: #f59e0b; /* solid */
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
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
        .form-card {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection
