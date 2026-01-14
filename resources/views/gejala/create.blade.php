{{-- resources/views/gejala/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Tambah Gejala Baru</h1>
    <p class="page-subtitle">Masukkan informasi gejala penyakit ayam</p>
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

    <form action="{{ route('gejala.store') }}" method="POST" novalidate>

        @csrf

        {{-- Kode Gejala --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-barcode"></i>
                Kode Gejala
            </label>
            <input
                type="text"
                name="kode_gejala"
                value="{{ $newCode ?? '' }}"
                readonly
                class="form-input input-readonly">
            <div class="input-hint">
                <i class="fas fa-info-circle"></i>
                Kode dibuat otomatis oleh sistem (G01, G02, dst)
            </div>
        </div>

        {{-- Nama / Pertanyaan Gejala --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-stethoscope"></i>
                Nama Gejala
                <span class="required">*</span>
            </label>
            <textarea
                name="nama_gejala"
                rows="4"
                placeholder="Contoh: Ayam tampak lesu dan nafsu makan menurun"
                class="form-input form-textarea">{{ old('nama_gejala') }}</textarea>
            <div class="input-hint">
                <i class="fas fa-lightbulb"></i>
                Deskripsikan gejala dengan jelas dan detail
            </div>
        </div>

        {{-- Tombol --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Data
            </button>

            <a href="{{ route('gejala.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </form>
</div>
@endsection
