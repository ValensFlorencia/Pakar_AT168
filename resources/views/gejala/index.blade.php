{{-- resources/views/gejala/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Gejala')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Data Gejala Ayam Petelur</h1>
    <p class="page-subtitle">Kelola daftar gejala yang digunakan pada proses diagnosa sistem pakar.</p>
</div>

<div class="form-card">

    {{-- ALERT ERROR (untuk pesan dari controller) --}}
    @if(session('error'))
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

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

    @php
        // Controller idealnya kirim: $usedGejalaIds = [...id gejala yang dipakai di CF/DS...]
        // Ini biar view tidak error kalau variabel belum ada.
        $usedGejalaIds = $usedGejalaIds ?? [];
    @endphp

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th style="width:140px;">Kode</th>
                    <th>Nama Gejala</th>
                    <th style="width:260px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($gejalas as $index => $gejala)
                @php
                    $isUsed = in_array($gejala->id, $usedGejalaIds, true);
                @endphp

                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="font-weight:700;">{{ $gejala->kode_gejala }}</td>
                    <td>{{ $gejala->nama_gejala }}</td>
                    <td style="text-align:center;">
                        <div class="action-group">

                            <a href="{{ route('gejala.edit', $gejala->id) }}" class="btn-mini btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            {{-- ✅ tombol tetap "Hapus", tapi kalau sudah dipakai -> alert & batal --}}
                            <form action="{{ route('gejala.destroy', $gejala->id) }}"
                                  method="POST"
                                  style="margin:0;"
                                  onsubmit="return {{ $isUsed
                                        ? "alert('Gejala ini tidak bisa dihapus karena sudah digunakan di basis pengetahuan/diagnosa.') || false"
                                        : "confirm('Yakin ingin menghapus gejala ini?')"
                                  }};">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-mini btn-delete {{ $isUsed ? 'btn-disabled' : '' }}">
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
    /* ALERT (umum) */
    .alert{
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .alert-icon{
        font-size: 15px;
        line-height: 1;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .alert-content{ flex: 1; }
    .alert-content strong{ font-weight: 500; }

    /* SUKSES */
    .alert-success{
        background:#ecfdf5;
        border:1px solid #86efac;
        color:#065f46;
    }

    /* ERROR */
    .alert-error{
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#991b1b;
    }

    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
        margin:0 auto;
    }

    .card-top{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
        flex-wrap:wrap;
    }

    .card-title{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#111827;
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
        color:#6b7280;
        font-weight:600;
        opacity:1;
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
        background:#fff;
    }

    .table thead{ background:#fffbeb; }

    .table th{
        padding:14px 14px;
        text-align:left;
        vertical-align:middle;
        border-bottom:1px solid #fde68a;
        color:#374151;
        font-weight:800;
        white-space:nowrap;
    }

    .table td{
        padding:14px 14px;
        text-align:left;
        vertical-align:middle;
        border-bottom:1px solid #fde68a;
        color:#111827;
        font-weight:500;
    }

    .table tbody tr:nth-child(even){ background:#fffef5; }
    .table tbody tr:hover{ background:#fff7ed; }

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
        color:#6b7280;
        font-weight:700;
        background:#fffef5;
    }

    /* Buttons */
    .btn{
        padding:12px 18px;
        border:none;
        border-radius:12px;
        font-size:14px;
        font-weight:900;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:10px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }
    .btn-submit:hover{
        background:#d97706;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    .btn-mini{
        padding:8px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:6px;
        border:none;
        transition:.2s ease;
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
        background:#fee2e2;
        color:#991b1b;
        border:1px solid #fecaca;
    }
    .btn-delete:hover{
        background:#fecaca;
        transform:translateY(-1px);
    }

    /* ✅ disabled state (tampil sama, tapi cursor tidak boleh + hover tidak berubah) */
    .btn-disabled{
        opacity:.75;
        cursor:not-allowed;
    }
    .btn-disabled:hover{
        transform:none;
        background:#fee2e2;
    }

    @media (max-width:768px){
        .form-card{ padding:24px; }
        .card-top{ flex-direction:column; align-items:stretch; }
        .btn{ width:100%; justify-content:center; }
        .action-group{ justify-content:flex-start; }
    }
</style>

@endsection
