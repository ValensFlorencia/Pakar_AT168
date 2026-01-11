@extends('layouts.app')

@section('title', 'Basis Pengetahuan Dempster Shafer')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Basis Pengetahuan Dempster Shafer</h1>
    <p class="page-subtitle">Relasi antara penyakit, gejala, dan bobot DS.</p>
</div>

<div class="form-card">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-layer-group"></i>
                Daftar Penyakit (Basis DS)
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Klik detail untuk melihat daftar gejala dan bobot DS pada penyakit tersebut.
            </div>
        </div>

        {{-- ✅ ROUTE TETAP DS --}}
        <a href="{{ route('basis_pengetahuan_ds.create') }}" class="btn btn-submit">
            <i class="fas fa-plus"></i>
            Tambah Basis DS
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

                    {{-- ✅ FIELD TETAP DS --}}
                    <td style="text-align:center;">
                        <span class="badge">{{ $p->basis_d_s_count }}</span>
                    </td>

                    <td style="text-align:center;">
                        {{-- ✅ ROUTE TETAP DS --}}
                        <a href="{{ route('basis_pengetahuan_ds.detail_penyakit', $p->id) }}"
                           class="btn-mini btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        Belum ada basis pengetahuan DS
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
