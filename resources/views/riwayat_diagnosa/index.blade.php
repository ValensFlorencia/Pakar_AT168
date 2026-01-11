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
                        <th style="width:220px;">User</th> {{-- ✅ tambah kolom user --}}
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

                            {{-- ✅ tampilkan user yang melakukan diagnosa --}}
                            <td>
                                <div class="judul">
                                    {{ $r->user->name ?? '—' }}
                                </div>
                                @if(!empty($r->user?->role))
                                    <div class="dt-time">
                                        {{ $r->user->role }}
                                    </div>
                                @endif
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

@endsection
