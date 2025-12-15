@extends('layouts.app')

@section('title', 'Detail Basis DS')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Detail Basis Pengetahuan DS</h1>
    <p class="page-subtitle">
        Penyakit: <b>{{ $penyakit->kode_penyakit }} - {{ $penyakit->nama_penyakit }}</b>
    </p>
</div>

<div class="card-wrap">

    <div class="card-header-row">
        <h2 class="card-title">Daftar Gejala & Bobot DS</h2>

        <a href="{{ route('basis_pengetahuan_ds.index') }}" class="btn btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="table-clean">
            <thead>
                <tr>
                    <th style="width:60px;text-align:center;">#</th>
                    <th style="width:140px;text-align:center;">Kode Gejala</th>
                    <th style="text-align:left;">Nama Gejala</th>
                    <th style="width:120px;text-align:center;">Bobot DS</th>
                    <th style="width:180px;text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($rules as $i => $row)
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td style="text-align:center;font-weight:600;">{{ $row->gejala->kode_gejala }}</td>
                    <td>{{ $row->gejala->nama_gejala }}</td>
                    <td style="text-align:center;">{{ number_format((float)$row->ds_value, 2) }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('basis_pengetahuan_ds.edit', $row->id) }}" class="pill pill-edit">
                                Edit
                            </a>

                            <form action="{{ route('basis_pengetahuan_ds.destroy', $row->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                  style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pill pill-delete">Hapus</button>
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
    .card-wrap{
        background:#ffffff;
        border-radius:20px;
        box-shadow:0 10px 30px rgba(0,0,0,0.08);
        padding:25px 30px 30px;
        border:1px solid #fde68a;
    }

    .card-header-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:18px;
        gap:12px;
    }

    .card-title{
        font-size:18px;
        font-weight:800;
        color:#5a4a2a;
        margin:0;
    }

    .table-clean{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        background:#ffffff;
        border-radius:12px;
        overflow:hidden;
    }

    .table-clean thead tr{
        background:#fff4bf;
    }

    .table-clean th,
    .table-clean td{
        padding:12px 10px;
        vertical-align:middle;
        border-bottom:1px solid #f1e4b5;
    }

    .table-clean tbody tr:nth-child(odd){
        background:#fffef5;
    }

    .table-clean tbody tr:nth-child(even){
        background:#fffbe2;
    }

    .table-clean tbody tr:hover{
        background:#fff9c4;
    }

    .actions{
        display:flex;
        gap:8px;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }

    .pill{
        padding:6px 14px;
        border-radius:999px;
        font-size:13px;
        font-weight:700;
        text-decoration:none;
        border:none;
        cursor:pointer;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        line-height:1.2;
        white-space:nowrap;
    }

    .pill-edit{
        background:#f59e0b;
        color:#fff;
    }
    .pill-edit:hover{ background:#d97706; }

    .pill-delete{
        background:#ffcccc;
        color:#b91c1c;
    }
    .pill-delete:hover{ background:#fca5a5; }

    /* Button back style (samain gaya create/edit) */
    .btn{
        padding:10px 18px;
        border-radius:10px;
        font-size:14px;
        font-weight:600;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        transition:all .2s ease;
        border:none;
        cursor:pointer;
        white-space:nowrap;
    }

    .btn-cancel{
        background:#f3f4f6;
        color:#5a4a2a;
        border:2px solid #e5e7eb;
    }

    .btn-cancel:hover{
        background:#e5e7eb;
        transform:translateY(-1px);
    }

    .empty{
        text-align:center;
        padding:20px;
        color:#6b7280;
        background:#fffef5;
    }

    @media (max-width:768px){
        .card-wrap{ padding:18px; }
        .card-header-row{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .actions{ justify-content:flex-start; }
    }
</style>

@endsection
