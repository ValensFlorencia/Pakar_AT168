@extends('layouts.app')

@section('title','Hasil Diagnosa')

@section('content')
@php
    $cfSorted = collect($hasilCF ?? [])->sortByDesc('nilai')->values();
    $dsSorted = collect($hasilDS ?? [])->sortByDesc('nilai')->values();

    $topCF = $cfSorted->first();
    $topDS = $dsSorted->first();

    $top3CF = $cfSorted->take(3)->values();
    $top3DS = $dsSorted->take(3)->values();

    $fmtVal = fn($v) => is_null($v) ? '-' : number_format((float)$v, 4);

    // utama: ikut controller (kalau ada), fallback ke DS lalu CF
    $utama = $topDS ?: $topCF;

    $gejalaList = collect($gejalaTerpilih ?? []);
@endphp

<div class="page-head">
    <div>
        <h1 class="page-title">Hasil Diagnosa Penyakit</h1>
        <p class="page-subtitle">
            Berikut hasil diagnosa berdasarkan gejala & bobot keyakinan yang kamu pilih.
        </p>
    </div>

    <div class="pill-wrap">
        <div class="pill">
            <span class="pill-label">Top CF</span>
            <span class="pill-value">
                @if($topCF)
                    {{ $topCF['kode'] ?? '-' }} — {{ $topCF['nama'] ?? '-' }} ({{ number_format((float)($topCF['persen'] ?? 0), 2) }}%)
                @else
                    -
                @endif
            </span>
        </div>
        <div class="pill">
            <span class="pill-label">Top DS</span>
            <span class="pill-value">
                @if($topDS)
                    {{ $topDS['kode'] ?? '-' }} — {{ $topDS['nama'] ?? '-' }} ({{ number_format((float)($topDS['persen'] ?? 0), 2) }}%)
                @else
                    -
                @endif
            </span>
        </div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="card">
    <div class="card-head">
        <h3>Kesimpulan</h3>
        <span class="badge {{ $utama ? 'badge-ok' : 'badge-warn' }}">
            {{ $utama ? 'Ada hasil dominan' : 'Belum ada dominan' }}
        </span>
    </div>

    {{-- Kesimpulan utama TANPA box panjang --}}
    <div class="conclusion-line">
        @if($utama)
            <span class="conclusion-label">Kesimpulan Utama:</span>
            <span class="conclusion-main">
                {{ $utama['nama'] ?? '-' }}
                <span class="conclusion-pct">({{ number_format((float)($utama['persen'] ?? 0), 2) }}%)</span>
            </span>

            @if(isset($metodeDominan))
                <span class="conclusion-meta">— Metode: <b>{{ $metodeDominan }}</b></span>
            @endif
        @else
            <span class="muted-inline">Tidak ditemukan penyakit dominan.</span>
        @endif
    </div>

    {{-- ✅ BLOK INI DIHAPUS supaya tulisan “3 kemungkinan ...” hilang --}}
    {{--
    @if(!empty($kesimpulan))
        <p class="conclusion-note">{{ $kesimpulan }}</p>
    @endif
    --}}

    <div class="divider"></div>

    {{-- TOP 3 CF + TOP 3 DS (dua box) --}}
    <div class="summary-grid">
        {{-- CF TOP 3 --}}
        <div class="summary-box">
            <div class="summary-title">Certainty Factor (Top 3)</div>

            @if($top3CF->isEmpty())
                <div class="summary-sub">Tidak ada hasil CF.</div>
            @else
                <div class="top3">
                    @foreach($top3CF as $i => $row)
                        <div class="top3-row {{ $i === 0 ? 'is-top' : '' }}">
                            <div class="top3-left">
                                <span class="rank">#{{ $i+1 }}</span>
                                <div class="who">
                                    <span class="code">{{ $row['kode'] ?? '-' }}</span>
                                    <span class="name">{{ $row['nama'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($row['nilai'] ?? null) }}</span>
                                <span class="pct">{{ number_format((float)($row['persen'] ?? 0), 2) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- DS TOP 3 --}}
        <div class="summary-box">
            <div class="summary-title">Dempster–Shafer (Top 3)</div>

            @if($top3DS->isEmpty())
                <div class="summary-sub">Tidak ada hasil DS.</div>
            @else
                <div class="top3">
                    @foreach($top3DS as $i => $row)
                        <div class="top3-row {{ $i === 0 ? 'is-top' : '' }}">
                            <div class="top3-left">
                                <span class="rank">#{{ $i+1 }}</span>
                                <div class="who">
                                    <span class="code">{{ $row['kode'] ?? '-' }}</span>
                                    <span class="name">{{ $row['nama'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($row['nilai'] ?? null) }}</span>
                                <span class="pct">{{ number_format((float)($row['persen'] ?? 0), 2) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Gejala yang dipilih --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Gejala yang Dipilih</h3>
        <span class="badge badge-neutral">
            {{ $gejalaList->count() }} gejala
        </span>
    </div>

    @if($gejalaList->isEmpty())
        <p class="muted" style="margin:0;">Data gejala terpilih tidak tersedia.</p>
    @else
        <div class="chips">
            @foreach($gejalaList as $g)
                <div class="chip">
                    <span class="chip-code">{{ $g['kode_gejala'] ?? '-' }}</span>
                    <span class="chip-name">{{ $g['nama_gejala'] ?? '-' }}</span>
                    <span class="chip-cf">CF user: <b>{{ $g['cf_user'] ?? '-' }}</b></span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Tabel CF --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Detail Nilai Certainty Factor (CF)</h3>
        <span class="badge badge-neutral">{{ $cfSorted->count() }} penyakit</span>
    </div>

    @if($cfSorted->isEmpty())
        <p class="muted" style="margin:0;">Tidak ada hasil CF untuk gejala yang dipilih.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:110px;">Kode</th>
                    <th>Nama Penyakit</th>
                    <th style="width:140px;">Nilai</th>
                    <th style="width:140px;">Persen</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cfSorted as $i => $row)
                    @php $isTop = ($i === 0); @endphp
                    <tr class="{{ $isTop ? 'top-row' : '' }}">
                        <td>{{ $i+1 }}</td>
                        <td><span class="tcode">{{ $row['kode'] ?? '-' }}</span></td>
                        <td>{{ $row['nama'] ?? '-' }}</td>
                        <td>{{ $fmtVal($row['nilai'] ?? null) }}</td>
                        <td>{{ number_format((float)($row['persen'] ?? 0), 2) }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Tabel DS --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Detail Nilai Dempster–Shafer (DS)</h3>
        <span class="badge badge-neutral">{{ $dsSorted->count() }} penyakit</span>
    </div>

    @if($dsSorted->isEmpty())
        <p class="muted" style="margin:0;">Tidak ada hasil DS untuk gejala yang dipilih.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:110px;">Kode</th>
                    <th>Nama Penyakit</th>
                    <th style="width:140px;">Nilai</th>
                    <th style="width:140px;">Persen</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dsSorted as $i => $row)
                    @php $isTop = ($i === 0); @endphp
                    <tr class="{{ $isTop ? 'top-row' : '' }}">
                        <td>{{ $i+1 }}</td>
                        <td><span class="tcode">{{ $row['kode'] ?? '-' }}</span></td>
                        <td>{{ $row['nama'] ?? '-' }}</td>
                        <td>{{ $fmtVal($row['nilai'] ?? null) }}</td>
                        <td>{{ number_format((float)($row['persen'] ?? 0), 2) }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Saran --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Saran Penanganan Awal</h3>
        <span class="badge badge-neutral">Rekomendasi</span>
    </div>

    @if($utama)
        <ul class="tips">
            <li><b>Isolasi sementara</b> ayam yang sakit untuk mencegah penularan (jika gejala mengarah ke penyakit menular).</li>
            <li><b>Periksa kondisi kandang:</b> kebersihan, ventilasi, dan kepadatan ayam.</li>
            <li><b>Observasi 24 jam:</b> catat perubahan nafsu makan, feses, dan pernapasan.</li>
            <li><b>Konfirmasi pakar/dokter hewan</b> untuk penanganan sesuai protokol.</li>
        </ul>
        <div class="muted" style="margin-top:10px;">
            Penyakit dominan: <b>{{ $utama['nama'] ?? '-' }}</b>
        </div>
    @else
        <p class="muted" style="margin:0;">
            Belum ada penyakit dominan. Coba pilih gejala dan bobot CF dengan lebih lengkap.
        </p>
    @endif
</div>

<style>
/* ===== tone sama dengan halaman diagnosa ===== */
.page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
}
.pill-wrap{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}
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
.pill-label{ color:#92400e; font-weight:800; flex-shrink:0; }
.pill-value{
    color:#78350f;
    font-weight:800;
    min-width:0;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* card */
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
    font-weight:800;
    color:#78350f;
}
.muted{ color:#92400e; font-weight:500; }

.badge{
    display:inline-flex;
    align-items:center;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    border:1px solid #fde68a;
    background:#fffbeb;
    color:#78350f;
}
.badge-ok{
    background:#ecfdf5;
    border-color:#86efac;
    color:#065f46;
}
.badge-warn{
    background:#fef2f2;
    border-color:#fecaca;
    color:#7f1d1d;
}
.badge-neutral{
    background:#fffef5;
    border-color:#fde68a;
    color:#92400e;
}

.divider{
    height:1px;
    background:#f3f4f6;
    margin:14px 0;
}

/* Kesimpulan ringkas */
.conclusion-line{
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:8px 10px;
    padding:10px 12px;
    border:1px solid #fde68a;
    border-radius:12px;
    background:#fffef5;
}
.conclusion-label{
    font-size:12px;
    font-weight:900;
    color:#92400e;
    text-transform:uppercase;
    letter-spacing:.3px;
}
.conclusion-main{
    font-size:14px;
    font-weight:900;
    color:#78350f;
}
.conclusion-pct{
    font-weight:800;
    color:#92400e;
    margin-left:6px;
}
.conclusion-meta{
    font-weight:700;
    color:#92400e;
}
.muted-inline{
    font-weight:600;
    color:#92400e;
}

/* summary grid */
.summary-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}
.summary-box{
    background:#fffef5;
    border:1px solid #fde68a;
    border-radius:14px;
    padding:12px 14px;
}
.summary-title{
    font-size:12px;
    color:#92400e;
    font-weight:800;
    margin-bottom:8px;
}
.summary-sub{
    margin-top:8px;
    font-size:13px;
    color:#92400e;
    font-weight:500;
}

/* Top 3 list */
.top3{ display:flex; flex-direction:column; gap:10px; }
.top3-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:12px 12px;
    border:1px solid #fde68a;
    border-radius:14px;
    background:#ffffff;
}
.top3-row.is-top{
    background:#fffbeb;
    border-color:#f59e0b;
    box-shadow:0 10px 22px rgba(245,158,11,0.12);
}
.top3-left{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}
.top3-left .rank{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:42px;
    height:36px;
    border-radius:12px;
    background:#fffef5;
    border:1px solid #fde68a;
    font-weight:800;
    color:#78350f;
    flex-shrink:0;
}
.top3-left .who{
    display:flex;
    flex-direction:column;
    gap:4px;
    min-width:0;
}
.top3-left .code{
    display:inline-flex;
    width:max-content;
    padding:3px 8px;
    border-radius:999px;
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#78350f;
    font-weight:800;
    font-size:12px;
}
.top3-left .name{
    color:#78350f;
    font-weight:800;
    font-size:13px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    max-width:360px;
}
.top3-right{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:2px;
    flex-shrink:0;
    min-width:110px;
}
.top3-right .val{ font-weight:900; color:#78350f; font-size:13px; }
.top3-right .pct{ font-weight:800; color:#92400e; font-size:12px; }

/* gejala chips */
.chips{ display:flex; flex-direction:column; gap:10px; }
.chip{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    background:#ffffff;
    border:1px solid #fde68a;
    border-radius:14px;
    padding:12px 14px;
}
.chip-code{
    display:inline-flex;
    padding:4px 10px;
    border-radius:999px;
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#78350f;
    font-weight:800;
    font-size:12px;
    white-space:nowrap;
}
.chip-name{
    flex:1;
    color:#78350f;
    font-weight:600;
    min-width:0;
}
.chip-cf{
    white-space:nowrap;
    color:#92400e;
    font-weight:600;
}

/* table */
.table-wrap{
    overflow:auto;
    border-radius:12px;
    border:1px solid #fde68a;
}
table{ width:100%; border-collapse:collapse; background:#fff; }
thead th{
    text-align:left;
    font-size:13px;
    color:#92400e;
    font-weight:800;
    padding:12px 12px;
    background:#fffbeb;
    border-bottom:1px solid #fde68a;
}
tbody td{
    padding:12px 12px;
    border-bottom:1px solid #f3f4f6;
    color:#78350f;
    font-weight:500;
}
.tcode{
    display:inline-flex;
    padding:4px 10px;
    border-radius:999px;
    background:#fffef5;
    border:1px solid #fde68a;
    font-weight:800;
    color:#78350f;
    font-size:12px;
}
.top-row{ background:#fff7ed; }
.top-row td{ font-weight:700; }

/* tips */
.tips{ margin:0; padding-left:18px; color:#78350f; }
.tips li{ margin:8px 0; font-weight:500; }

@media (max-width: 900px){
    .summary-grid{ grid-template-columns:1fr; }
    .chip{ flex-direction:column; align-items:flex-start; }
    .chip-cf{ width:100%; }

    .top3-right{ align-items:flex-start; }
    .top3-row{ flex-direction:column; align-items:flex-start; }
    .top3-left .name{ max-width:100%; white-space:normal; }
    .pill{ max-width:100%; }
}
</style>

@endsection
