{{-- resources/views/gejala/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Gejala Ayam Petelur</h1>
    <p class="page-subtitle">Kelola daftar gejala yang digunakan pada proses diagnosa sistem pakar.</p>
</div>

<div class="form-card">

    <div class="card-top">
        <div>
            <h2 class="card-title">
                <i class="fas fa-list-check"></i>
                Daftar Gejala
            </h2>
            <div class="card-subtitle">
                <i class="fas fa-info-circle"></i>
                Data gejala yang akan dipakai pada proses diagnosa.
            </div>
        </div>

        <a href="{{ route('gejala.create') }}" class="btn btn-submit">
            <i class="fas fa-plus"></i>
            Tambah Gejala
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:140px;">Kode</th>
                    <th>Nama Gejala</th>
                    <th style="width:220px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($gejalas as $index => $gejala)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="font-weight:700;">{{ $gejala->kode_gejala }}</td>
                    <td>{{ $gejala->nama_gejala }}</td>
                    <td style="text-align:center;">
                        <div class="action-group">

                            <a href="{{ route('gejala.edit', $gejala->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            <form action="{{ route('gejala.destroy', $gejala->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus gejala ini?');"
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
                    <td colspan="4" class="empty">
                        Belum ada data gejala.
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
        color:#000;
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
        color:#000;
        opacity:.85;
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

    .table th, .table td{
        padding:12px 12px;
        text-align:left;
        vertical-align:top;
        border-bottom:1px solid #fde68a;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff9c4; }

    .action-group{
        display:flex;
        gap:10px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .empty{
        text-align:center;
        padding:22px;
        color:#000;
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
        background:#f59e0b; /* SOLID */
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.3);
    }
    .btn-submit:hover{
        background:#d97706;
        transform:translateY(-2px);
        box-shadow:0 6px 16px rgba(245,158,11,0.4);
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

    .btn-edit{
        background:#f59e0b;
        color:#fff;
        box-shadow:0 4px 12px rgba(245,158,11,.22);
    }
    .btn-edit:hover{
        background:#d97706;
        transform:translateY(-1px);
    }

    .btn-delete{
        background:#fef2f2;
        color:#b91c1c;
        border:1px solid #fecaca;
    }
    .btn-delete:hover{
        background:#fee2e2;
        transform:translateY(-1px);
    }
    .page-title{
    color:#111827;   /* hitam elegan */
    }

    .page-subtitle{
        color:#374151;   /* abu gelap */
    }


    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
    }
</style>

@endsection
