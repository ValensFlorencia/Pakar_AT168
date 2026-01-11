{{-- resources/views/gejala/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Gejala</h1>
    <p class="page-subtitle">Perbarui informasi gejala penyakit ayam</p>
</div>

<div class="form-card">

    {{-- ✅ ALERT VALIDASI (biar keliatan kalau gagal) --}}
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

    <form action="{{ route('gejala.update', $gejala->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ✅ kirim kode_gejala walau input tampilan readonly --}}
        <input type="hidden" name="kode_gejala" value="{{ $gejala->kode_gejala }}">

        {{-- KODE GEJALA (tampilan saja) --}}
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
@endsection
