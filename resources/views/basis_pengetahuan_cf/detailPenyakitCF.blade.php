@extends('layouts.app')

@section('title', 'Detail Basis CF')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Detail Basis Pengetahuan CF</h1>
    <p class="page-subtitle">
        Penyakit: <b>{{ $penyakit->kode_penyakit }} - {{ $penyakit->nama_penyakit }}</b>
    </p>
</div>

<div class="form-card">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-list-check"></i>
                Daftar Gejala & Bobot CF
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Data relasi gejala untuk penyakit terpilih (beserta bobot CF).
            </div>
        </div>

        <a href="{{ route('basis_pengetahuan_cf.index') }}" class="btn btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:140px; text-align:center;">Kode Gejala</th>
                    <th>Nama Gejala</th>
                    <th style="width:120px; text-align:center;">Bobot CF</th>
                    <th style="width:200px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rules as $i => $row)
                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td style="text-align:center;">{{ $row->gejala->kode_gejala }}</td>
                    <td>{{ $row->gejala->nama_gejala }}</td>
                    <td style="text-align:center;">
                        <span class="badge">{{ $row->cf_value }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div class="action-group">
                            <a href="{{ route('basis_pengetahuan_cf.edit', $row->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            <form action="{{ route('basis_pengetahuan_cf.destroy', $row->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                  style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-mini btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        Belum ada gejala untuk penyakit ini.
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
        color:#111827; /* hitam */
        display:flex;
        align-items:center;
        gap:10px;
    }

    .card-title i{ color:#111827; } /* icon ikut hitam */

    .card-subtitle{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:#111827; /* hitam */
        opacity:.8;
    }

    .card-subtitle i{ font-size:12px; }

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

    .action-group{
        display:flex;
        gap:10px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .btn-mini{
        border:none;
        border-radius:999px;
        padding:7px 12px;
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

    .btn-edit{
        background:#f59e0b;
        color:#ffffff; /* tetap putih */
        box-shadow:0 4px 12px rgba(245,158,11,.25);
    }
    .btn-edit:hover{
        background:#d97706;
        transform:translateY(-1px);
    }

    .btn-delete{
        background:#fef2f2;
        color:#111827; /* hitam */
        border:1px solid #fecaca;
    }
    .btn-delete:hover{
        background:#fee2e2;
        transform:translateY(-1px);
    }

    .empty{
        text-align:center;
        padding:22px;
        color:#111827; /* hitam */
        font-weight:600;
        background:#fffbeb;
    }

    /* tombol kembali */
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
    }

    .btn-cancel{
        background:#f3f4f6;
        color:#111827; /* hitam */
    }
    .btn-cancel:hover{
        background:#e5e7eb;
        transform:translateY(-2px);
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
    }

</style>

@endsection
