{{-- resources/views/gejala/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Gejala')

@section('content')

<h1 class="page-title">Form Tambah Gejala</h1>

<div class="card" style="padding:35px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">

    {{-- ALERT VALIDASI --}}
    @if ($errors->any())
        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 16px;
            border-radius:10px;
            margin-bottom:20px;
            font-size:14px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin-left:18px; margin-top:6px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gejala.store') }}" method="POST">
        @csrf

        {{-- Kode Gejala --}}
        <div class="form-group" style="margin-bottom:25px;">
            <label style="font-weight:600;">Kode Gejala</label>
            <input
                type="text"
                name="kode_gejala"
                value="{{ $newCode ?? '' }}"
                readonly
                style="
                    width:100%;
                    padding:15px;
                    border:2px solid #ffe4b3;
                    border-radius:12px;
                    background:#fff3c4;
                    font-weight:600;
                    cursor:not-allowed;
                ">
            <div class="input-hint" style="font-size:13px; margin-top:5px;">
                Kode dibuat otomatis oleh sistem (G01, G02, dst)
            </div>
        </div>

        {{-- Nama / Pertanyaan Gejala --}}
        <div class="form-group" style="margin-bottom:25px;">
            <label style="font-weight:600;">Nama Gejala</label>
            <input
                type="text"
                name="nama_gejala"
                value="{{ old('nama_gejala') }}"
                placeholder="Contoh: Ayam tampak lesu dan nafsu makan menurun"
                required
                style="
                    width:100%;
                    padding:15px;
                    border:2px solid #ffe4b3;
                    background:#fffdf7;
                    border-radius:12px;
                ">
        </div>

        {{-- Tombol --}}
        <div style="display:flex; gap:15px; margin-top:30px;">
            <button
                type="submit"
                style="
                    padding:14px 35px;
                    border:none;
                    border-radius:25px;
                    font-size:16px;
                    font-weight:600;
                    background:linear-gradient(135deg,#f6b93b,#e58e26);
                    color:white;
                    cursor:pointer;
                ">
                Simpan
            </button>

            <a href="{{ route('gejala.index') }}"
                style="
                    padding:14px 35px;
                    background:#e0e0e0;
                    color:#5a4a2a;
                    border-radius:25px;
                    font-weight:600;
                    text-decoration:none;
                ">
                Kembali
            </a>
        </div>

    </form>
</div>

@endsection
