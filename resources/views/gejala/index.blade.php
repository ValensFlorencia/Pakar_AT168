{{-- resources/views/gejala/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Gejala')

@section('content')

    <h1 class="page-title">Data Gejala Ayam Petelur</h1>
    <p class="page-subtitle">
        Kelola daftar gejala yang digunakan pada proses diagnosa sistem pakar.
    </p>

    {{-- CARD DAFTAR GEJALA --}}
    <div style="
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 25px 30px 30px;
    ">

        {{-- BARIS ATAS: JUDUL + TOMBOL --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:18px; font-weight:700; color:#5a4a2a; margin:0;">
                Daftar Gejala
            </h2>

            <a href="{{ route('gejala.create') }}"
               style="
                    padding:10px 22px;
                    border-radius:999px;
                    background:linear-gradient(135deg,#f6b93b,#e58e26);
                    color:#ffffff;
                    text-decoration:none;
                    font-weight:600;
                    box-shadow:0 4px 15px rgba(246,185,59,0.35);
               ">
                + Tambah Gejala
            </a>
        </div>

        {{-- TABEL --}}
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                <tr style="background:#fff4bf;">
                    <th style="padding:10px 12px; text-align:left;">#</th>
                    <th style="padding:10px 12px; text-align:left;">Kode</th>
                    <th style="padding:10px 12px; text-align:left;">Nama Gejala</th>
                    <th style="width:130px;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($gejalas as $index => $gejala)
                    <tr style="background: {{ $index % 2 === 0 ? '#fffef5' : '#fffbe2' }};">
                        <td style="padding:10px 12px;">{{ $index + 1 }}</td>
                        <td style="padding:10px 12px; font-weight:600;">{{ $gejala->kode_gejala }}</td>
                        <td style="padding:10px 12px;">{{ $gejala->nama_gejala }}</td>
                        <td style="padding:8px 12px;">
                        <div style="display:flex; gap:8px; justify-content:center;">

                            <a href="{{ route('gejala.edit', $gejala->id) }}"
                            style="
                                padding:6px 14px;
                                border-radius:999px;
                                background:#f6b93b;
                                color:#fff;
                                font-size:13px;
                                font-weight:600;
                                text-decoration:none;
                            ">
                                Edit
                            </a>

                            <form action="{{ route('gejala.destroy', $gejala->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus gejala ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="
                                        padding:6px 14px;
                                        border-radius:999px;
                                        background:#ffcccc;
                                        color:#b91c1c;
                                        border:none;
                                        font-size:13px;
                                        font-weight:600;
                                        cursor:pointer;
                                    ">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:14px 12px; text-align:center; color:#6b7280;">
                            Belum ada data gejala.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>

@endsection
