{{-- resources/views/gejala/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Gejala')

@section('content')

    <h1 class="page-title">Form Edit Gejala</h1>

    <div style="
        background:#ffffff;
        border-radius:20px;
        box-shadow:0 10px 30px rgba(0,0,0,0.08);
        padding:30px 30px 35px;
        max-width:800px;
    ">

        <form action="{{ route('gejala.update', $gejala->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- KODE --}}
            <div style="margin-bottom:22px;">
                <label style="font-weight:600; margin-bottom:6px; display:block;">Kode Gejala</label>
                <input type="text"
                       value="{{ $gejala->kode_gejala }}"
                       readonly
                       style="
                           width:100%; padding:12px 14px;
                           border-radius:12px;
                           border:2px solid #ffe4b3;
                           background:#fff3c4;
                           font-weight:600;
                           cursor:not-allowed;
                       ">
                <small style="font-size:12px; color:#8b7355;">
                    Kode tidak dapat diubah.
                </small>
            </div>

            {{-- NAMA --}}
            <div style="margin-bottom:22px;">
                <label style="font-weight:600; margin-bottom:6px; display:block;">Nama / Pertanyaan Gejala</label>
                <input type="text"
                       name="nama_gejala"
                       value="{{ old('nama_gejala', $gejala->nama_gejala) }}"
                       required
                       style="
                           width:100%; padding:12px 14px;
                           border-radius:12px;
                           border:2px solid #ffe4b3;
                           background:#fffdf7;
                       ">
            </div>

            {{-- KETERANGAN --}}
            <div style="margin-bottom:22px;">
                <label style="font-weight:600; margin-bottom:6px; display:block;">Keterangan (opsional)</label>
                <textarea name="keterangan"
                          rows="3"
                          style="
                              width:100%; padding:12px 14px;
                              border-radius:12px;
                              border:2px solid #ffe4b3;
                              background:#fffdf7;
                              resize:vertical;
                          ">{{ old('keterangan', $gejala->keterangan) }}</textarea>
            </div>

            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit"
                        style="
                            padding:12px 30px;
                            border-radius:999px;
                            border:none;
                            cursor:pointer;
                            font-weight:600;
                            background:linear-gradient(135deg,#f6b93b,#e58e26);
                            color:white;
                        ">
                    Perbarui
                </button>

                <a href="{{ route('gejala.index') }}"
                   style="
                       padding:12px 30px;
                       border-radius:999px;
                       background:#e0e0e0;
                       color:#5a4a2a;
                       text-decoration:none;
                       font-weight:600;
                   ">
                    Kembali
                </a>
            </div>

        </form>
    </div>

@endsection
