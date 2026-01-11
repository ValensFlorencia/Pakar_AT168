{{-- resources/views/penyakit/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penyakit')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Penyakit</h1>
    <p class="page-subtitle">Perbarui informasi penyakit yang digunakan pada proses diagnosa</p>
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

    <form action="{{ route('penyakit.update', $penyakit->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- KODE PENYAKIT --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-barcode"></i>
                Kode Penyakit
            </label>
            <input
                type="text"
                value="{{ $penyakit->kode_penyakit }}"
                readonly
                class="form-input input-readonly">
            <div class="input-hint">
                <i class="fas fa-lock"></i>
                Kode tidak dapat diubah
            </div>
        </div>

        {{-- NAMA PENYAKIT --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit
                <span class="required">*</span>
            </label>
            <input
                type="text"
                name="nama_penyakit"
                value="{{ old('nama_penyakit', $penyakit->nama_penyakit) }}"
                required
                class="form-input"
                placeholder="Contoh: Newcastle Disease (ND)">
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Gunakan nama penyakit sesuai istilah yang umum digunakan
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-align-left"></i>
                Deskripsi
                <span class="required">*</span>
            </label>
            <textarea
                name="deskripsi"
                rows="4"
                required
                class="form-input form-textarea"
                placeholder="Masukkan deskripsi penyakit...">{{ old('deskripsi', $penyakit->deskripsi) }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Jelaskan ciri umum penyakit dan dampaknya pada ayam
            </div>
        </div>

        {{-- SOLUSI / PENANGANAN --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-notes-medical"></i>
                Solusi / Penanganan
                <span class="required">*</span>
            </label>
            <textarea
                name="solusi"
                rows="4"
                required
                class="form-input form-textarea"
                placeholder="Masukkan solusi / penanganan...">{{ old('solusi', $penyakit->solusi) }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Tulis langkah penanganan (obat, isolasi, vaksin, dll)
            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Perbarui Data
            </button>

            <a href="{{ route('penyakit.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </form>
</div>
@endsection
