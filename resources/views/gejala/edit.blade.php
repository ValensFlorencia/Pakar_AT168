{{-- resources/views/gejala/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Gejala</h1>
    <p class="page-subtitle">Perbarui informasi gejala penyakit ayam</p>
</div>

<div class="form-card">

    <form action="{{ route('gejala.update', $gejala->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- KODE GEJALA --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-barcode"></i>
                Kode Gejala
            </label>
            <input
                type="text"
                value="{{ $gejala->kode_gejala }}"
                readonly
                class="form-input input-readonly">
            <div class="input-hint">
                <i class="fas fa-lock"></i>
                Kode tidak dapat diubah
            </div>
        </div>

        {{-- NAMA GEJALA --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-stethoscope"></i>
                Nama Gejala
                <span class="required">*</span>
            </label>
            <textarea
                name="nama_gejala"
                rows="4"
                required
                class="form-input form-textarea">{{ old('nama_gejala', $gejala->nama_gejala) }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Deskripsikan gejala dengan jelas dan detail
            </div>
        </div>

        {{-- KETERANGAN --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-file-alt"></i>
                Keterangan
                <span class="optional">(opsional)</span>
            </label>
            <textarea
                name="keterangan"
                rows="3"
                class="form-input form-textarea"
                placeholder="Tambahkan catatan atau informasi tambahan...">{{ old('keterangan', $gejala->keterangan) }}</textarea>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Perbarui Data
            </button>

            <a href="{{ route('gejala.index') }}" class="btn btn-cancel">
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

    .optional {
        color: #000;
        font-weight: 400;
        font-size: 13px;
        opacity: 0.7;
        margin-left: 4px;
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
        min-height: 100px;
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
        background: #f59e0b;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-submit:hover {
        background: #d97706;
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
