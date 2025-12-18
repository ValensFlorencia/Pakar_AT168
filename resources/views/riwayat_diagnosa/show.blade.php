@extends('layouts.app')

@section('title', 'Detail Riwayat Diagnosa')

@section('content')
@php
    $p = $riwayat->payload ?? [];
    $gejala = $p['gejala_terpilih'] ?? [];
    $ranking = $p['ranking'] ?? [];
    $kesimpulan = $p['kesimpulan'] ?? null;

    $isGrouped = is_array($ranking) && (array_key_exists('cf', $ranking) || array_key_exists('ds', $ranking));
    $groups = $isGrouped
        ? [
            'CF' => $ranking['cf'] ?? [],
            'DS' => $ranking['ds'] ?? [],
          ]
        : [
            'Hasil' => $ranking,
          ];
@endphp

<div class="page-head">
    <div>
        <h1 class="page-title">Detail Riwayat Diagnosa</h1>
        <p class="page-subtitle">
            Riwayat hasil diagnosa yang tersimpan (tanggal, gejala, kesimpulan, dan ranking).
        </p>
    </div>

    <div class="pill-wrap">
        <div class="pill">
            <span class="pill-label">Tanggal</span>
            <span class="pill-value">
                {{ ($riwayat->diagnosa_at ?? $riwayat->created_at)->format('d M Y H:i') }} WIB
            </span>
        </div>

        <a href="{{ route('riwayat-diagnosa.index') }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
    </div>
</div>

{{-- Ringkasan --}}
<div class="card">
    <div class="card-head">
        <h3>{{ $riwayat->judul ?? 'Hasil Diagnosa' }}</h3>
        <span class="badge badge-neutral">Tersimpan</span>
    </div>

    <p class="muted" style="margin:0;">
        {{ $kesimpulan ?? 'Tidak ada kesimpulan tersimpan.' }}
    </p>
</div>

{{-- Gejala yang dipilih --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Gejala yang Dipilih</h3>
        <span class="badge badge-neutral">{{ is_array($gejala) ? count($gejala) : 0 }} gejala</span>
    </div>

    @if(empty($gejala))
        <p class="muted" style="margin:0;">Tidak ada data gejala tersimpan.</p>
    @else
        <div class="chips">
            @foreach($gejala as $g)
                @if(is_array($g))
                    <div class="chip">
                        <span class="chip-code">{{ $g['kode_gejala'] ?? ($g['kode'] ?? '-') }}</span>
                        <span class="chip-name">{{ $g['nama_gejala'] ?? ($g['nama'] ?? ($g['gejala'] ?? '-')) }}</span>
                        <span class="chip-cf">
                            CF user:
                            <b>{{ $g['cf_user'] ?? '-' }}</b>
                        </span>
                    </div>
                @else
                    <div class="chip">
                        <span class="chip-code">-</span>
                        <span class="chip-name">{{ $g }}</span>
                        <span class="chip-cf">CF user: <b>-</b></span>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

{{-- Ranking (CF/DS atau Hasil) --}}
@if(empty($ranking))
    <div class="card" style="margin-top:16px;">
        <div class="card-head">
            <h3>Hasil Diagnosa (Ranking)</h3>
            <span class="badge badge-warn">Kosong</span>
        </div>
        <p class="muted" style="margin:0;">Tidak ada ranking tersimpan.</p>
    </div>
@else
    @foreach($groups as $label => $list)
        @php
            $judul = $label === 'CF'
                ? 'Detail Nilai Certainty Factor (CF)'
                : ($label === 'DS'
                    ? 'Detail Nilai Dempster–Shafer (DS)'
                    : 'Detail Hasil Diagnosa');
        @endphp

        <div class="card" style="margin-top:16px;">
            <div class="card-head">
                <h3>{{ $judul }}</h3>
                <span class="badge badge-neutral">{{ is_array($list) ? count($list) : 0 }} data</span>
            </div>

            @if(empty($list))
                <p class="muted" style="margin:0;">Data {{ $label }} tidak tersedia.</p>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Nama Penyakit</th>
                            <th style="width:160px;">Nilai</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $idx => $row)
                            @php
                                $nama  = $row['nama'] ?? $row['nama_penyakit'] ?? $row['penyakit'] ?? '-';
                                $kode  = $row['kode'] ?? $row['kode_penyakit'] ?? null;
                                $nilai = $row['nilai'] ?? $row['score'] ?? null;
                                $isTop = ($idx === 0);
                            @endphp
                            <tr class="{{ $isTop ? 'top-row' : '' }}">
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    @if($kode)
                                        <span class="tcode">{{ $kode }}</span>
                                        <span style="margin-left:8px;">{{ $nama }}</span>
                                    @else
                                        {{ $nama }}
                                    @endif
                                </td>
                                <td>{{ is_null($nilai) ? '-' : round((float)$nilai, 6) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
@endif

<style>
/* ===== SAMAKAN DENGAN HALAMAN HASIL DIAGNOSA ===== */
.page-head{
    max-width:1800px;
    margin:0 auto 14px;
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
}

/* pill & action */
.pill-wrap{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
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

.btn-back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:999px;
    background:#ffffff;
    border:1px solid #fde68a;
    color:#78350f;
    text-decoration:none;
    font-weight:800;
    font-size:13px;
    transition:all .2s ease;
}
.btn-back:hover{
    transform: translateY(-1px);
    border-color:#f59e0b;
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

/* badges */
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
.badge-neutral{
    background:#fffef5;
    border-color:#fde68a;
    color:#92400e;
}
.badge-warn{
    background:#fef2f2;
    border-color:#fecaca;
    color:#7f1d1d;
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

@media (max-width: 900px){
    .chip{ flex-direction:column; align-items:flex-start; }
    .chip-cf{ width:100%; }
}
</style>
@endsection
