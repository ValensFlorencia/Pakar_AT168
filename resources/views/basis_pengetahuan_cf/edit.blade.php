@extends('layouts.app')

@section('title', 'Edit Basis Pengetahuan CF')

@section('content')

<h1 class="page-title">Edit Basis Pengetahuan CF</h1>

<div style="
    background:#ffffff;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    padding:25px 30px 35px;
    max-width:1000px;
">

    {{-- ALERT VALIDASI --}}
    @if ($errors->any())
        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 16px;
            border-radius:10px;
            margin-bottom:20px;
            font-size:14px;
        ">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin-left:18px; margin-top:6px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('basis_pengetahuan_cf.update', $basisCF->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- NAMA PENYAKIT --}}
        <div class="form-group" style="margin-bottom:25px;">
            <label style="font-weight:600;">Nama Penyakit</label>

            <select disabled
                    style="width:100%; padding:15px;
                        border:2px solid #ffe4b3;
                        border-radius:12px;
                        background:#fffdf7;">
                <option>
                    {{ $basisCF->penyakit->kode_penyakit }} - {{ $basisCF->penyakit->nama_penyakit }}
                </option>
            </select>

            <input type="hidden" name="penyakit_id" value="{{ $basisCF->penyakit_id }}">

        </div>

        {{-- CARD GEJALA + CF (SAMA SEPERTI CREATE, TAPI 1 BARIS SAJA) --}}
        <div style="
            background:#f9fafb;
            border-radius:18px;
            border:1px solid #e5e7eb;
            padding:20px 22px 18px;
            margin-bottom:25px;
        ">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:15px;">
                <span style="font-size:20px;">📋</span>
                <div>
                    <div style="font-weight:600;">Gejala &amp; Bobot Certainty Factor</div>
                    <div style="font-size:12px; color:#6b7280;">
                        Ubah gejala dan bobot certainty factor untuk basis pengetahuan ini.
                    </div>
                </div>
            </div>

            <div class="gejala-row"
                 style="display:flex; gap:12px; margin-bottom:10px; align-items:center;">

                {{-- DROPDOWN GEJALA --}}
                <select disabled
                        style="flex:2; padding:12px 14px;
                            border-radius:12px;
                            border:1px solid #e5e7eb;
                            background:#ffffff;">
                    <option>
                        {{ $basisCF->gejala->kode_gejala }} - {{ $basisCF->gejala->nama_gejala }}
                    </option>
                </select>

                <input type="hidden" name="gejala_id" value="{{ $basisCF->gejala_id }}">



                {{-- DROPDOWN CF --}}
                @php
                    $cfOptions = [
                        '0'   => '0 - Tidak',
                        '0.2' => '0.2 - Sedikit Yakin',
                        '0.4' => '0.4 - Cukup Yakin',
                        '0.6' => '0.6 - Hampir Yakin',
                        '0.8' => '0.8 - Yakin',
                        '1'   => '1 - Sangat Yakin',
                    ];

                    // samakan format dari DB jadi 1 angka di belakang koma
                    $currentCF = number_format((float)$basisCF->cf_value, 1); // contoh: 0.20 -> "0.2", 1.00 -> "1.0"
                @endphp

                <select name="cf_value" required
                        style="flex:1; padding:12px 14px;
                            border-radius:12px;
                            border:1px solid #e5e7eb;
                            background:#ffffff;">
                    <option value="">-- Bobot CF --</option>

                    @foreach($cfOptions as $val => $label)
                        <option value="{{ $val }}"
                            {{ $currentCF === number_format((float)$val, 1) ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>


            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div style="display:flex; gap:15px; margin-top:10px;">
            <button type="submit"
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
                Simpan Perubahan
            </button>

            <a href="{{ route('basis_pengetahuan_cf.index') }}"
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
