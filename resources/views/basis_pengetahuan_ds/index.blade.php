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
<style>
    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
    }

    .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
    }

    .card-title{
        margin:0;
        font-size:18px;
        font-weight:700;
        color:#111827; /* SAMA DENGAN CF */
        display:flex;
        align-items:center;
        gap:10px;
    }

    .card-title i{ color:#f59e0b; }

    .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#111827; /* hitam */
        opacity:.75;   /* SAMA DENGAN CF */
    }

    .card-subtitle i{ font-size:12px; }

    /* Table */
    .table-wrap{
        overflow-x:auto;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#ffffff;
    }

    .table{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        background:white;
    }

    .table thead{
        background:#fff9c4;
    }

    .table th,
    .table td{
        padding:12px 12px;
        text-align:left;
        vertical-align:top;
        border-bottom:1px solid #fde68a;
        color:#111827; /* hitam */
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff9c4; }

    .badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:54px;
        padding:6px 10px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        font-weight:700;
        color:#111827; /* hitam */
        font-size:13px;
    }

    .empty{
        text-align:center;
        padding:22px;
        color:#111827; /* hitam */
        font-weight:600;
        background:#fffbeb;
    }

    /* Buttons */
    .btn{
        padding:13px 28px;
        border:none;
        border-radius:10px;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-submit:hover{
        background:#d97706;
        transform: translateY(-2px);
        box-shadow:0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-mini{
        border:none;
        border-radius:999px;
        padding:8px 14px;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-detail{
        background:#fff9c4;
        color:#111827; /* hitam */
    }

    .btn-detail:hover{
        background:#fff9c4;
        transform: translateY(-1px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
    }
</style>

@endsection
