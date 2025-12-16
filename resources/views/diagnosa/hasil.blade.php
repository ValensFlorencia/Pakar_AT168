@extends('layouts.app')

@section('title','Hasil Diagnosa')

@section('content')
@php
    $cfSorted = collect($hasilCF ?? [])->sortByDesc('nilai')->values();
    $dsSorted = collect($hasilDS ?? [])->sortByDesc('nilai')->values();

    $topCF = $cfSorted->first();
    $topDS = $dsSorted->first();

    // util tampil angka
    $fmtVal = fn($v) => is_null($v) ? '-' : number_format((float)$v, 4);
    $fmtPct = fn($v) => is_null($v) ? '-' : number_format(((float)$v) * 100, 2) . '%';

    // penyakit utama: prioritas DS, fallback CF
    $utama = $topDS ?: $topCF;

    // gejala terpilih (kalau dikirim dari controller)
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
                @if($topCF) {{ $topCF['kode'] }} ({{ $topCF['persen'] }}%) @else - @endif
            </span>
        </div>
        <div class="pill">
            <span class="pill-label">Top DS</span>
            <span class="pill-value">
                @if($topDS) {{ $topDS['kode'] }} ({{ $topDS['persen'] }}%) @else - @endif
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

    <p class="muted" style="margin:0;">
        {{ $kesimpulan ?? 'Tidak ditemukan kesimpulan.' }}
    </p>

    <div class="divider"></div>

    <div class="summary-grid">
        <div class="summary-box">
            <div class="summary-title">Certainty Factor (Top)</div>
            @if($topCF)
                <div class="summary-main">
                    <span class="code">{{ $topCF['kode'] }}</span>
                    <span class="name">{{ $topCF['nama'] }}</span>
                </div>
                <div class="summary-sub">
                    Nilai: <b>{{ $fmtVal($topCF['nilai']) }}</b> · Persen: <b>{{ $topCF['persen'] }}%</b>
                </div>
            @else
                <div class="summary-sub">Tidak ada hasil CF.</div>
            @endif
        </div>

        <div class="summary-box">
            <div class="summary-title">Dempster–Shafer (Top)</div>
            @if($topDS)
                <div class="summary-main">
                    <span class="code">{{ $topDS['kode'] }}</span>
                    <span class="name">{{ $topDS['nama'] }}</span>
                </div>
                <div class="summary-sub">
                    Nilai: <b>{{ $fmtVal($topDS['nilai']) }}</b> · Persen: <b>{{ $topDS['persen'] }}%</b>
                </div>
            @else
                <div class="summary-sub">Tidak ada hasil DS.</div>
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
                        <td><span class="tcode">{{ $row['kode'] }}</span></td>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $fmtVal($row['nilai'] ?? null) }}</td>
                        <td>{{ ($row['persen'] ?? null) !== null ? number_format((float)$row['persen'],2).'%' : '-' }}</td>
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
                        <td><span class="tcode">{{ $row['kode'] }}</span></td>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $fmtVal($row['nilai'] ?? null) }}</td>
                        <td>{{ ($row['persen'] ?? null) !== null ? number_format((float)$row['persen'],2).'%' : '-' }}</td>
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
.page-title{
    font-size:28px;
    font-weight:800;
    color:#78350f;
    margin:0;
}
.page-subtitle{
    margin:6px 0 0;
    color:#92400e;
    font-weight:500;
}

/* pill top result */
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
}
.pill-label{ color:#92400e; font-weight:700; }
.pill-value{ color:#78350f; font-weight:800; }

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
    font-weight:700;
    margin-bottom:8px;
}
.summary-main{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}
.summary-main .code{
    display:inline-flex;
    padding:4px 10px;
    border-radius:999px;
    background:#fff;
    border:1px solid #fde68a;
    font-weight:800;
    color:#78350f;
    font-size:12px;
}
.summary-main .name{
    color:#78350f;
    font-weight:700;
}
.summary-sub{
    margin-top:8px;
    font-size:13px;
    color:#92400e;
    font-weight:500;
}

/* gejala chips */
.chips{
    display:flex;
    flex-direction:column;
    gap:10px;
}
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
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}
thead th{
    text-align:left;
    font-size:13px;
    color:#92400e;
    font-weight:700;
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
.top-row{
    background:#fff7ed;
}
.top-row td{
    font-weight:700;
}

/* tips */
.tips{
    margin:0;
    padding-left:18px;
    color:#78350f;
}
.tips li{
    margin:8px 0;
    font-weight:500;
}

@media (max-width: 900px){
    .summary-grid{ grid-template-columns:1fr; }
    .chip{ flex-direction:column; align-items:flex-start; }
    .chip-cf{ width:100%; }
}
</style>

@endsection
