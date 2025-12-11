{{-- resources/views/penyakit/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penyakit')

@section('content')
    <h1 class="page-title">Form Edit Penyakit</h1>
    <p class="page-subtitle">
        Perbarui informasi penyakit yang digunakan pada proses diagnosa sistem pakar.
    </p>

    <div class="card" style="max-width: 900px;">

        <form action="{{ route('penyakit.update', $penyakit->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Kode Penyakit --}}
            <div class="form-group" style="margin-bottom:25px;">
                <label style="font-weight:600; margin-bottom:8px; display:block;">Kode Penyakit</label>
                <input
                    type="text"
                    value="{{ $penyakit->kode_penyakit }}"
                    readonly
                    style="
                        width:100%;
                        padding:15px;
                        border:2px solid #ffe4b3;
                        border-radius:12px;
                        background:#fff3c4;
                        font-weight:600;
                        cursor:not-allowed;
                        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    "
                >
                <div class="input-hint" style="font-size:12px; color:#8b7355; margin-top:5px;">
                    Kode tidak dapat diubah.
                </div>
            </div>

            {{-- Nama Penyakit --}}
            <div class="form-group" style="margin-bottom:25px;">
                <label style="font-weight:600; margin-bottom:8px; display:block;">Nama Penyakit</label>
                <input
                    type="text"
                    name="nama_penyakit"
                    value="{{ old('nama_penyakit', $penyakit->nama_penyakit) }}"
                    required
                    style="
                        width:100%;
                        padding:15px;
                        border:2px solid #ffe4b3;
                        border-radius:12px;
                        background:#fffdf7;
                        font-size:15px;
                        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    "
                >
            </div>

            {{-- Deskripsi --}}
            <div class="form-group" style="margin-bottom:25px;">
                <label style="font-weight:600; margin-bottom:8px; display:block;">Deskripsi</label>
                <textarea
                    name="deskripsi"
                    required
                    style="
                        width:100%;
                        min-height:120px;
                        padding:15px;
                        border:2px solid #ffe4b3;
                        border-radius:12px;
                        background:#fffdf7;
                        font-size:15px;
                        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        line-height:1.5;
                        resize:vertical;
                    "
                >{{ old('deskripsi', $penyakit->deskripsi) }}</textarea>
            </div>

            {{-- Solusi / Penanganan --}}
            <div class="form-group" style="margin-bottom:25px;">
                <label style="font-weight:600; margin-bottom:8px; display:block;">Solusi / Penanganan</label>
                <textarea
                    name="solusi"
                    required
                    style="
                        width:100%;
                        min-height:120px;
                        padding:15px;
                        border:2px solid #ffe4b3;
                        border-radius:12px;
                        background:#fffdf7;
                        font-size:15px;
                        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        line-height:1.5;
                        resize:vertical;
                    "
                >{{ old('solusi', $penyakit->solusi) }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="button-group" style="display:flex; gap:15px; margin-top:30px;">
                <button type="submit"
                        class="btn btn-primary"
                        style="
                            padding:14px 32px;
                            border:none;
                            border-radius:25px;
                            cursor:pointer;
                            font-weight:600;
                            font-size:16px;
                            background:linear-gradient(135deg,#f6b93b 0%,#e58e26 100%);
                            color:white;
                            box-shadow:0 4px 15px rgba(246,185,59,0.3);
                        ">
                    Perbarui
                </button>

                <a href="{{ route('penyakit.index') }}"
                   class="btn btn-secondary"
                   style="
                        padding:14px 32px;
                        border-radius:25px;
                        background:#e0e0e0;
                        color:#5a4a2a;
                        text-decoration:none;
                        font-weight:600;
                        font-size:16px;
                   ">
                    Kembali
                </a>
            </div>

        </form>
    </div>
@endsection
