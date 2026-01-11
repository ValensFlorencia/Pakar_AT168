@extends('layouts.app')

@section('title', 'Edit Basis Pengetahuan CF')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Edit Basis Pengetahuan CF</h1>
    <p class="page-subtitle">Ubah bobot Certainty Factor untuk gejala pada penyakit yang dipilih</p>
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

    <form action="{{ route('basis_pengetahuan_cf.update', $basisCF->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- PENYAKIT (DISABLED) --}}
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-virus"></i>
                Nama Penyakit
            </label>

            <select disabled class="form-input input-readonly">
                <option>
                    {{ $basisCF->penyakit->kode_penyakit }} - {{ $basisCF->penyakit->nama_penyakit }}
                </option>
            </select>

            <input type="hidden" name="penyakit_id" value="{{ $basisCF->penyakit_id }}">

            <div class="input-hint">
                <i class="fas fa-lock"></i>
                Penyakit tidak dapat diubah
            </div>
        </div>

        {{-- SECTION GEJALA + CF --}}
        <div class="gejala-section">
            <div class="section-label">
                <i class="fas fa-list-check"></i>
                Gejala & Bobot Certainty Factor
            </div>

            <div class="row-wrap">

                {{-- GEJALA (DISABLED) --}}
                <div class="col">
                    <label class="form-label" style="margin-bottom:10px;">
                        <i class="fas fa-stethoscope"></i>
                        Gejala
                    </label>

                    <select disabled class="form-input input-readonly">
                        <option>
                            {{ $basisCF->gejala->kode_gejala }} - {{ $basisCF->gejala->nama_gejala }}
                        </option>
                    </select>

                    <input type="hidden" name="gejala_id" value="{{ $basisCF->gejala_id }}">

                    <div class="input-hint">
                        <i class="fas fa-lock"></i>
                        Gejala tidak dapat diubah
                    </div>
                </div>

                {{-- CF VALUE (EDITABLE) --}}
                <div class="col">
                    <label class="form-label" style="margin-bottom:10px;">
                        <i class="fas fa-sliders-h"></i>
                        Bobot CF <span class="required">*</span>
                    </label>

                    @php
                        $currentCF = (string)((float)$basisCF->cf_value);
                        $cfOptions = ['0','0.2','0.4','0.6','0.8','1'];
                    @endphp

                    <select name="cf_value" required class="form-input">
                        <option value="">-- Bobot CF --</option>
                        @foreach($cfOptions as $v)
                            <option value="{{ $v }}"
                                {{ old('cf_value', $currentCF) === (string)((float)$v) ? 'selected' : '' }}>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>

                    <div class="input-hint">
                        <i class="fas fa-info-circle"></i>
                        Pilih bobot keyakinan pakar untuk gejala ini
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('basis_pengetahuan_cf.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

    </form>
</div>
@endsection
