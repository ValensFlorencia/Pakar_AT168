@extends('layouts.app')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Riwayat Diagnosa</h1>
        <p class="page-subtitle">
            Daftar hasil diagnosa yang pernah dibuat (tanggal & jam + ringkasan).
        </p>
    </div>

    <div class="pill-wrap">
        @if(request('start_date') || request('end_date'))
            <div class="pill">
                <span class="pill-label">Periode</span>
                <span class="pill-value">
                    {{ request('start_date') ?? '-' }} s/d {{ request('end_date') ?? '-' }}
                </span>
            </div>
        @else
            <div class="pill">
                <span class="pill-label">Status</span>
                <span class="pill-value">Menampilkan semua data</span>
            </div>
        @endif

        <div class="pill">
            <span class="pill-label">Total</span>
            <span class="pill-value">{{ $riwayats->total() ?? $riwayats->count() }} data</span>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-head">
        <h3>Filter Periode</h3>
        <span class="badge badge-neutral">Riwayat</span>
    </div>

    <div class="filter-wrap">
        <form method="GET" action="{{ route('riwayat-diagnosa.index') }}" class="filter-form">
            <div class="filter-field">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}">
            </div>

            <div class="filter-field">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-primary">
                    Tampilkan
                </button>

                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('riwayat-diagnosa.index') }}" class="btn-ghost">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if(request('start_date') || request('end_date'))
            <div class="filter-meta">
                📊 Menampilkan periode:
                <b>{{ request('start_date') ?? '-' }}</b> s/d <b>{{ request('end_date') ?? '-' }}</b>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="flash-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="divider"></div>

    @if($riwayats->count() === 0)
        <div class="empty-state">
            <div class="empty-ico">📋</div>
            <div class="empty-title">Belum ada riwayat diagnosa.</div>
            <div class="empty-sub">Silakan lakukan diagnosa terlebih dahulu, lalu hasilnya akan tersimpan di sini.</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:170px;">Tanggal & Jam</th>
                        <th style="width:260px;">Judul</th>
                        <th>Ringkasan (Top DS/CF)</th>
                        <th style="width:140px;text-align:center;">Aksi</th> {{-- ✅ center --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach($riwayats as $r)
                        @php
                            $p = $r->payload ?? [];

                            $ds0 = $p['ranking']['ds'][0] ?? null;
                            $cf0 = $p['ranking']['cf'][0] ?? null;

                            $topDSNama = is_array($ds0) ? ($ds0['nama'] ?? $ds0['nama_penyakit'] ?? $ds0['penyakit'] ?? null) : null;
                            $topDSKode = is_array($ds0) ? ($ds0['kode'] ?? $ds0['kode_penyakit'] ?? null) : null;
                            $topDSPct  = is_array($ds0) ? ($ds0['persen'] ?? null) : null;

                            $topCFNama = is_array($cf0) ? ($cf0['nama'] ?? $cf0['nama_penyakit'] ?? $cf0['penyakit'] ?? null) : null;
                            $topCFKode = is_array($cf0) ? ($cf0['kode'] ?? $cf0['kode_penyakit'] ?? null) : null;
                            $topCFPct  = is_array($cf0) ? ($cf0['persen'] ?? null) : null;

                            $dt = $r->diagnosa_at ?? $r->created_at;

                            $fmtPct = function($v){
                                if (is_null($v)) return null;
                                return number_format((float)$v, 2) . '%';
                            };
                        @endphp

                        <tr class="row-hover">
                            <td>
                                <div class="dt-date">
                                    {{ optional($dt)->timezone('Asia/Jakarta')->format('d M Y') }}
                                </div>
                                <div class="dt-time">
                                    {{ optional($dt)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </div>
                            </td>

                            <td>
                                <div class="judul">
                                    {{ $r->judul ?? 'Hasil Diagnosa' }}
                                </div>
                            </td>

                            <td class="ringkasan">
                                <div class="mini-pills">
                                    <div class="mini-pill">
                                        <span class="mini-label">Top DS</span>
                                        <span class="mini-value">
                                            @if($topDSNama)
                                                {{ $topDSKode ? $topDSKode.' — ' : '' }}{{ $topDSNama }}
                                                @if(!is_null($topDSPct))
                                                    ({{ $fmtPct($topDSPct) }})
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>

                                    <div class="mini-pill">
                                        <span class="mini-label">Top CF</span>
                                        <span class="mini-value">
                                            @if($topCFNama)
                                                {{ $topCFKode ? $topCFKode.' — ' : '' }}{{ $topCFNama }}
                                                @if(!is_null($topCFPct))
                                                    ({{ $fmtPct($topCFPct) }})
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td style="text-align:center;"> {{-- ✅ center --}}
                                <a href="{{ route('riwayat-diagnosa.show', $r->id) }}" class="btn-primary btn-sm btn-center">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="pagination-wrap" style="margin-top:16px;">
            {{ $riwayats->links() }}
        </div>
    @endif
</div>

<style>
.page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
}
.pill-wrap{ display:flex; gap:10px; flex-wrap:wrap; }
.pill{
    background:#fffbeb;
    border:1px solid #fde68a;
    border-radius:999px;
    padding:10px 12px;
    display:flex;
    gap:10px;
    align-items:center;
    font-size:13px;
    max-width:820px;
}
.pill-label{ color:#000; font-weight:600; flex-shrink:0; }
.pill-value{
    color:#000;
    font-weight:600;
    min-width:0;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.card{
    max-width:1800px;
    margin:0 auto;
    background:#fff;
    border:1px solid #fde68a;
    border-radius:16px;
    padding:18px 20px;
    box-shadow:0 4px 16px rgba(0,0,0,0.06);
}
.card-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
}
.card h3{
    margin:0;
    font-size:16px;
    font-weight:600;
    color:#000;
}
.badge{
    display:inline-flex;
    align-items:center;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    border:1px solid #fde68a;
    background:#fffbeb;
    color:#000;
}
.badge-neutral{
    background:#fffef5;
    border-color:#fde68a;
    color:#000;
}
.divider{
    height:1px;
    background:#f3f4f6;
    margin:14px 0;
}
.filter-form{
    display:flex;
    gap:12px;
    align-items:flex-end;
    flex-wrap:wrap;
    margin:0;
}
.filter-field{
    display:flex;
    flex-direction:column;
    gap:6px;
    min-width:180px;
}
.filter-field label{
    font-size:12px;
    font-weight:600;
    color:#000;
}
.filter-field input{
    padding:10px 12px;
    border-radius:12px;
    border:1px solid #fde68a;
    background:#fff;
    color:#000;
    font-weight:700;
    outline:none;
}
.filter-field input:focus{
    border-color:#f59e0b;
    box-shadow:0 0 0 4px rgba(245,158,11,0.14);
}
.filter-actions{
    display:flex;
    gap:10px;
    align-items:flex-end;
    margin-left:0;
}

.filter-meta{
    margin-top:10px;
    color:#000;
    font-weight:600;
    font-size:13px;
}

.btn-primary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:11px 18px;
    border-radius:12px;
    background:#f59e0b;
    color:#fff;
    text-decoration:none;
    border:none;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 20px rgba(245,158,11,0.22);
    transition:all .15s ease;
    white-space:nowrap;
}
.btn-primary:hover{ background:#d97706; transform: translateY(-1px); }
.btn-ghost{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:11px 18px;
    border-radius:12px;
    background:#ffffff;
    color:#000;
    text-decoration:none;
    border:1px solid #fde68a;
    font-weight:900;
    transition:all .15s ease;
    white-space:nowrap;
}
.btn-ghost:hover{
    border-color:#f59e0b;
    box-shadow:0 0 0 4px rgba(245,158,11,0.14);
    transform: translateY(-1px);
}
.btn-sm{ padding:10px 14px; border-radius:999px; font-weight:600; }

/* ✅ tombol di tengah kolom */
.btn-center{ margin:0 auto; }

.flash-success{
    background:#ecfdf5;
    border:1px solid #86efac;
    color:#065f46;
    font-weight:600;
    padding:12px 14px;
    border-radius:14px;
    box-shadow:0 10px 26px rgba(0,0,0,0.06);
}

.empty-state{
    padding:18px 14px;
    border:1px dashed #fde68a;
    border-radius:14px;
    background:#fffef5;
    text-align:center;
}
.empty-ico{ font-size:44px; margin-bottom:10px; }
.empty-title{ font-weight:600; color:#000; font-size:14px; }
.empty-sub{ color:#000; font-weight:600; font-size:13px; margin-top:4px; }

.table-wrap{ overflow:auto; border-radius:12px; border:1px solid #fde68a; }
table{ width:100%; border-collapse:collapse; background:#fff; }
thead th{
    text-align:left;
    font-size:13px;
    color:#000;
    font-weight:600;
    padding:12px 12px;
    background:#fffbeb;
    border-bottom:1px solid #fde68a;
    white-space:nowrap;
}
tbody td{
    padding:14px 12px;
    border-bottom:1px solid #f3f4f6;
    color:#000;
    font-weight:500;
    vertical-align:top;
}
.row-hover:hover{ background:#fffbeb; }

.dt-date{ font-size:13px; color:#000; font-weight:600;}
.dt-time{ margin-top:4px; font-size:13px; color:#d97706; font-weight:600; }
.judul{ font-size:14px; font-weight:600; color:#000; }

/* Stack pills */
.mini-pills{
    margin-top:0;
    display:flex;
    flex-direction:column;
    gap:10px;
    align-items:flex-start;
}
.mini-pill{
    width:100%;
    background:#fffef5;
    border:1px solid #fde68a;
    border-radius:999px;
    padding:10px 12px;
    display:flex;
    gap:10px;
    align-items:center;
    font-size:12.5px;
}
.mini-label{
    color:#000;
    font-weight:600;
    flex-shrink:0;
    min-width:58px;
}
.mini-value{
    color:#000;
    font-weight:600;
    min-width:0;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* Pagination */
.pagination-wrap nav[role="navigation"]{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
    padding:12px 14px;
    background:#fffef5;
    border:1px solid #fde68a;
    border-radius:14px;
}
.pagination-wrap p{
    margin:0;
    color:#000;
    font-size:13px;
    font-weight:600;
    line-height:1.4;
}
.pagination-wrap .inline-flex{ display:inline-flex !important; gap:8px !important; flex-wrap:wrap; }
.pagination-wrap a{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 14px;
    border-radius:999px;
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#000;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    transition:all .18s ease;
    white-space:nowrap;
}
.pagination-wrap a:hover{
    background:#fffef5;
    border-color:#f59e0b;
    transform:translateY(-1px);
}
.pagination-wrap span[aria-disabled="true"] span{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 14px;
    border-radius:999px;
    background:#f3f4f6;
    border:1px solid #e5e7eb;
    color:#6b7280;
    font-size:13px;
    font-weight:600;
    white-space:nowrap;
}
.pagination-wrap span[aria-current="page"] span{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 14px;
    border-radius:999px;
    background:#f59e0b;
    border:1px solid #f59e0b;
    color:#ffffff;
    font-size:13px;
    font-weight:600;
    white-space:nowrap;
}

@media (max-width: 900px){
    .filter-actions{ margin-left:0; width:100%; justify-content:flex-start; }
    thead th{ white-space:normal; }
}
@media (max-width:768px){
    .pagination-wrap nav[role="navigation"]{ justify-content:center; text-align:center; }
}
</style>
@endsection
