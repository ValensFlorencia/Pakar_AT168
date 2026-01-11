@extends('layouts.app')

@section('title', 'Basis Pengetahuan Certainty Factor')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Basis Pengetahuan Certainty Factor</h1>
    <p class="page-subtitle">Relasi antara penyakit, gejala, dan bobot certainty factor.</p>
</div>

<div class="form-card">

    {{-- ✅ ALERT SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-layer-group"></i>
                Daftar Penyakit (Basis CF)
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Klik detail untuk melihat daftar gejala dan bobot CF pada penyakit tersebut.
            </div>
        </div>

        <a href="{{ route('basis_pengetahuan_cf.create') }}" class="btn btn-submit">
            <i class="fas fa-plus"></i>
            Tambah Basis CF
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:160px; text-align:center;">Kode Penyakit</th>
                    <th>Nama Penyakit</th>
                    <th style="width:160px; text-align:center;">Jumlah Gejala</th>
                    <th style="width:160px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($penyakits as $i => $p)
                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td style="text-align:center;">{{ $p->kode_penyakit }}</td>
                    <td>{{ $p->nama_penyakit }}</td>
                    <td style="text-align:center;">
                        <span class="badge">{{ $p->basis_c_f_count }}</span>
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('basis_pengetahuan_cf.detail_penyakit', $p->id) }}"
                           class="btn-mini btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        Belum ada basis pengetahuan CF
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
