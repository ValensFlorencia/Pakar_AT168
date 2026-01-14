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
        {{-- ✅ tambah id + novalidate supaya bisa intercept submit --}}
        <form id="filter-riwayat-form" method="GET" action="{{ route('riwayat-diagnosa.index') }}" class="filter-form" novalidate>
            <div class="filter-field">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" id="start_date">
            </div>

            <div class="filter-field">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" id="end_date">
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
                        <th style="width:220px;">User</th>
                        <th style="width:260px;">Judul</th>
                        <th>Ringkasan (Top DS/CF)</th>
                        <th style="width:140px;text-align:center;">Aksi</th>
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

                            <td style="text-align:center;">
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

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ CSS SweetAlert kuning kecil (hanya modal) --}}
<style>
.swal2-popup.swal-yellow {
    width: 360px !important;
    padding: 18px 20px !important;
    border-radius: 14px;
    border: 2px solid #f6c453;
    box-shadow: 0 12px 35px rgba(0,0,0,.18);
}
.swal2-popup.swal-yellow .swal2-icon {
    width: 56px !important;
    height: 56px !important;
    margin: 12px auto 8px !important;
}
.swal2-popup.swal-yellow .swal2-icon.swal2-warning {
    border-color: #f6c453 !important;
    color: #f6c453 !important;
}
.swal2-popup.swal-yellow .swal2-title {
    font-size: 18px !important;
    font-weight: 700;
    margin: 6px 0 4px !important;
    color: #3a2a00;
}
.swal2-popup.swal-yellow .swal2-html-container {
    font-size: 14px !important;
    line-height: 1.4;
    margin: 4px 0 10px !important;
    color: #5a4300;
}
.swal2-popup.swal-yellow .swal2-confirm.btn-yellow {
    font-size: 13px !important;
    padding: 8px 16px !important;
    border-radius: 10px !important;
    background: #f6c453 !important;
    color: #3a2a00 !important;
    border: 0 !important;
    font-weight: 700 !important;
    box-shadow: 0 6px 16px rgba(246,196,83,.35);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form  = document.getElementById('filter-riwayat-form');
    const start = document.getElementById('start_date');
    const end   = document.getElementById('end_date');
    if (!form || !start || !end) return;

    const swalWarn = (title, msg) => Swal.fire({
        icon: 'warning',
        title,
        text: msg,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: { popup: 'swal-yellow', confirmButton: 'btn-yellow' }
    });

    form.addEventListener('submit', (e) => {
        const s = start.value; // format: YYYY-MM-DD atau ""
        const t = end.value;

        // Kalau salah satu kosong: biarkan normal (filter opsional)
        if (!s || !t) return;

        // Rule: start_date harus <= end_date (lebih kecil atau sama)
        if (s > t) {
            e.preventDefault();
            swalWarn('Periode Tidak Valid', '"Dari Tanggal" harus lebih kecil / sama dengan "Sampai Tanggal".');
            start.focus();
        }
    });
});
</script>

@endsection
