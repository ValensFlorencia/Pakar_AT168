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
@endsection
