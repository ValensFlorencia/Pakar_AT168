@extends('layouts.app')

@section('title', 'Edit Basis Pengetahuan DS')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Basis Pengetahuan DS</h1>
    <p class="page-subtitle">Perbarui bobot Dempster Shafer untuk gejala pada penyakit tertentu</p>
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

    <form action="{{ route('basis_pengetahuan_ds.update', $basisDS->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- NAMA PENYAKIT (DISABLED) --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit
            </label>

            <select disabled class="form-input input-readonly">
                <option>
                    {{ $basisDS->penyakit->kode_penyakit }} - {{ $basisDS->penyakit->nama_penyakit }}
                </option>
            </select>

            {{-- hidden agar tetap terkirim (fungsi tetap) --}}
            <input type="hidden" name="penyakit_id" value="{{ $basisDS->penyakit_id }}">

            <div class="input-hint">
                <i class="fas fa-lock"></i>
                Penyakit tidak dapat diubah
            </div>
        </div>

        {{-- SECTION GEJALA & DS --}}
        <div class="gejala-section">

            <div class="section-label">
                <i class="fas fa-list-check"></i>
                Gejala & Bobot Dempster Shafer
            </div>

            <div class="gejala-row">

                {{-- GEJALA (DISABLED) --}}
                <select disabled class="form-input gejala-select">
                    <option>
                        {{ $basisDS->gejala->kode_gejala }} - {{ $basisDS->gejala->nama_gejala }}
                    </option>
                </select>

                <input type="hidden" name="gejala_id" value="{{ $basisDS->gejala_id }}">

                @php
                    $currentDS = (string)((float)$basisDS->ds_value);
                    $dsOptions = ['0','0.2','0.4','0.6','0.8','1'];
                @endphp

                {{-- BOBOT DS --}}
                <select name="ds_value" required class="form-input ds-select">
                    <option value="">-- Bobot DS --</option>
                    @foreach($dsOptions as $v)
                        <option value="{{ $v }}"
                            {{ old('ds_value', $currentDS) === (string)((float)$v) ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="input-hint" style="margin-top:14px;">
                <i class="fas fa-lightbulb"></i>
                Ubah bobot DS sesuai keyakinan pakar (0 sampai 1)
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('basis_pengetahuan_ds.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </form>
</div>
@endsection
