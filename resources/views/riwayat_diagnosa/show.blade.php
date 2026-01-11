@extends('layouts.app')

@section('title', 'Detail Riwayat Diagnosa')

@section('content')
@php
    $p = $riwayat->payload ?? [];

    $gejalaList = collect($p['gejala_terpilih'] ?? $p['gejalaTerpilih'] ?? []);

    // ranking bisa grouped {cf:[], ds:[]} atau flat
    $ranking = $p['ranking'] ?? [];
    $cfSorted = collect($ranking['cf'] ?? []);
    $dsSorted = collect($ranking['ds'] ?? []);

    // fallback kalau payload lama: mungkin langsung list tanpa group
    if($cfSorted->isEmpty() && $dsSorted->isEmpty() && is_array($ranking) && array_is_list($ranking)){
        // anggap sebagai hasil umum -> taruh di CF biar tetap tampil
        $cfSorted = collect($ranking);
    }

    // normalize field helpers
    $getKode = fn($row) => is_array($row) ? ($row['kode'] ?? $row['kode_penyakit'] ?? null) : null;
    $getNama = fn($row) => is_array($row) ? ($row['nama'] ?? $row['nama_penyakit'] ?? $row['penyakit'] ?? null) : null;
    $getNilai = fn($row) => is_array($row) ? ($row['nilai'] ?? $row['score'] ?? null) : null;

    // persen: pakai row['persen'] kalau ada, kalau tidak ada coba (nilai*100) kalau nilai <= 1
    $getPersen = function($row) use ($getNilai){
        if(!is_array($row)) return null;
        if(array_key_exists('persen', $row) && !is_null($row['persen'])) return (float)$row['persen'];
        $n = $getNilai($row);
        if(is_null($n)) return null;
        $n = (float)$n;
        return ($n <= 1.0) ? ($n * 100.0) : null;
    };

    $cfSorted = $cfSorted->values();
    $dsSorted = $dsSorted->values();

    $topCF = $cfSorted->first();
    $topDS = $dsSorted->first();

    $top3CF = $cfSorted->take(3)->values();
    $top3DS = $dsSorted->take(3)->values();

    // metode dominan (kalau kamu simpan), fallback: DS kalau ada topDS, kalau tidak CF
    $metodeDominan = $p['metodeDominan'] ?? ($topDS ? 'DS' : ($topCF ? 'CF' : null));

    // utama: pakai payload kalau ada, fallback: DS lalu CF
    $utama = $p['utama'] ?? null;
    if(!$utama){
        $utama = $topDS ?: $topCF;
    }

    $fmtVal = fn($v) => is_null($v) ? '-' : number_format((float)$v, 4);
    $fmtPct = fn($v) => is_null($v) ? '-' : number_format((float)$v, 2) . '%';

    $dt = $riwayat->diagnosa_at ?? $riwayat->created_at;

    // ✅ solusiMap dari controller: [kode_penyakit => solusi]
    $solusiMap = $solusiMap ?? [];
    $getSolusi = fn($kode) => $kode ? ($solusiMap[$kode] ?? null) : null;
@endphp

<div class="page-head">
    <div>
        <h1 class="page-title">Detail Riwayat Diagnosa</h1>
        <p class="page-subtitle">
            Riwayat hasil diagnosa yang tersimpan (tanggal, gejala, dan ranking).
        </p>
    </div>

    <div class="pill-wrap">
        <div class="pill">
            <span class="pill-label">Tanggal</span>
            <span class="pill-value">
                {{ optional($dt)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
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

{{-- PILL TOP CF / TOP DS (kayak hasil diagnosa) --}}
<div class="pill-top-wrap">
    <div class="pill big">
        <span class="pill-label">Top CF</span>
        <span class="pill-value">
            @if($topCF)
                {{ $getKode($topCF) ?? '-' }} — {{ $getNama($topCF) ?? '-' }} ({{ $fmtPct($getPersen($topCF)) }})
            @else
                -
            @endif
        </span>
    </div>

    <div class="pill big">
        <span class="pill-label">Top DS</span>
        <span class="pill-value">
            @if($topDS)
                {{ $getKode($topDS) ?? '-' }} — {{ $getNama($topDS) ?? '-' }} ({{ $fmtPct($getPersen($topDS)) }})
            @else
                -
            @endif
        </span>
    </div>
</div>

{{-- CARD KESIMPULAN (TANPA TEKS "3 kemungkinan..." / kesimpulan panjang) --}}
<div class="card">
    <div class="card-head">
        <h3>Kesimpulan</h3>
        <span class="badge {{ $utama ? 'badge-ok' : 'badge-warn' }}">
            {{ $utama ? 'Ada hasil dominan' : 'Belum ada dominan' }}
        </span>
    </div>

    <div class="conclusion-line">
        @if($utama)
            <span class="conclusion-label">Kesimpulan Utama:</span>
            <span class="conclusion-main">
                {{ $getNama($utama) ?? '-' }}
                <span class="conclusion-pct">({{ $fmtPct($getPersen($utama)) }})</span>
            </span>

            <span class="conclusion-meta">— Metode: <b>{{ $metodeDominan ?? '-' }}</b></span>
        @else
            <span class="muted-inline">Tidak ditemukan penyakit dominan.</span>
        @endif
    </div>

    <div class="divider"></div>

    {{-- TOP 3 (dua box besar) --}}
    <div class="summary-grid">
        {{-- CF TOP 3 --}}
        <div class="summary-box">
            <div class="summary-title">Certainty Factor (Top 3)</div>

            @if($top3CF->isEmpty())
                <div class="summary-sub">Tidak ada hasil CF.</div>
            @else
                <div class="top3">
                    @foreach($top3CF as $i => $row)
                        @php
                            $kode = $getKode($row);
                            $nama = $getNama($row) ?? '-';
                            $sol  = $getSolusi($kode);
                        @endphp

                        <button type="button"
                                class="top3-row clickable {{ $i === 0 ? 'is-top' : '' }}"
                                data-kode="{{ e($kode) }}"
                                data-nama="{{ e($nama) }}"
                                data-solusi="{{ e($sol ?? '') }}">
                            <div class="top3-left">
                                <span class="rank">#{{ $i+1 }}</span>
                                <div class="who">
                                    <span class="code">{{ $getKode($row) ?? '-' }}</span>
                                    <span class="name">{{ $getNama($row) ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($getNilai($row)) }}</span>
                                <span class="pct">{{ $fmtPct($getPersen($row)) }}</span>
                            </div>
                        </button>
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
                        @php
                            $kode = $getKode($row);
                            $nama = $getNama($row) ?? '-';
                            $sol  = $getSolusi($kode);
                        @endphp

                        <button type="button"
                                class="top3-row clickable {{ $i === 0 ? 'is-top' : '' }}"
                                data-kode="{{ e($kode) }}"
                                data-nama="{{ e($nama) }}"
                                data-solusi="{{ e($sol ?? '') }}">
                            <div class="top3-left">
                                <span class="rank">#{{ $i+1 }}</span>
                                <div class="who">
                                    <span class="code">{{ $getKode($row) ?? '-' }}</span>
                                    <span class="name">{{ $getNama($row) ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($getNilai($row)) }}</span>
                                <span class="pct">{{ $fmtPct($getPersen($row)) }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL SOLUSI --}}
<div id="solusiModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="solusiTitle">
        <div class="modal-head">
            <div>
                <div class="modal-kode" id="solusiKode">-</div>
                <div class="modal-title" id="solusiTitle">Solusi Penanganan</div>
            </div>
            <button type="button" class="modal-close" id="solusiClose">✕</button>
        </div>

        <div class="modal-body" id="solusiBody">-</div>
    </div>
</div>

{{-- GEJALA TERPILIH --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Gejala yang Dipilih</h3>
        <span class="badge badge-neutral">{{ $gejalaList->count() }} gejala</span>
    </div>

    @if($gejalaList->isEmpty())
        <p class="muted" style="margin:0;">Tidak ada data gejala tersimpan.</p>
    @else
        <div class="chips">
            @foreach($gejalaList as $g)
                @php
                    $kodeG = is_array($g) ? ($g['kode_gejala'] ?? $g['kode'] ?? '-') : '-';
                    $namaG = is_array($g) ? ($g['nama_gejala'] ?? $g['nama'] ?? $g['gejala'] ?? '-') : (string)$g;
                    $cfU   = is_array($g) ? ($g['cf_user'] ?? '-') : '-';
                @endphp
                <div class="chip">
                    <span class="chip-code">{{ $kodeG }}</span>
                    <span class="chip-name">{{ $namaG }}</span>
                    <span class="chip-cf">CF user: <b>{{ $cfU }}</b></span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- TABEL DETAIL CF --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Detail Nilai Certainty Factor (CF)</h3>
        <span class="badge badge-neutral">{{ $cfSorted->count() }} penyakit</span>
    </div>

    @if($cfSorted->isEmpty())
        <p class="muted" style="margin:0;">Tidak ada hasil CF tersimpan.</p>
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
                        <tr class="{{ $i === 0 ? 'top-row' : '' }}">
                            <td>{{ $i+1 }}</td>
                            <td><span class="tcode">{{ $getKode($row) ?? '-' }}</span></td>
                            <td>{{ $getNama($row) ?? '-' }}</td>
                            <td>{{ $fmtVal($getNilai($row)) }}</td>
                            <td>{{ $fmtPct($getPersen($row)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- TABEL DETAIL DS --}}
<div class="card" style="margin-top:16px;">
    <div class="card-head">
        <h3>Detail Nilai Dempster–Shafer (DS)</h3>
        <span class="badge badge-neutral">{{ $dsSorted->count() }} penyakit</span>
    </div>

    @if($dsSorted->isEmpty())
        <p class="muted" style="margin:0;">Tidak ada hasil DS tersimpan.</p>
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
                        <tr class="{{ $i === 0 ? 'top-row' : '' }}">
                            <td>{{ $i+1 }}</td>
                            <td><span class="tcode">{{ $getKode($row) ?? '-' }}</span></td>
                            <td>{{ $getNama($row) ?? '-' }}</td>
                            <td>{{ $fmtVal($getNilai($row)) }}</td>
                            <td>{{ $fmtPct($getPersen($row)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<script>
const solusiByKode = @json($solusiMap ?? []); // ✅ jangan pakai {}

(function () {
    const modal    = document.getElementById('solusiModal');
    const btnClose = document.getElementById('solusiClose');

    const elKode  = document.getElementById('solusiKode');
    const elTitle = document.getElementById('solusiTitle');
    const elBody  = document.getElementById('solusiBody');

    if (!modal) return;

    function openModal(kode, nama) {
        const key = String(kode || '').trim().toUpperCase();

        // kalau solusiByKode array, aksesnya tetap bisa pakai ["P01"]
        const solusi = solusiByKode[key];

        elKode.textContent = key || '-';
        elTitle.textContent = nama ? `Solusi Penanganan — ${nama}` : 'Solusi Penanganan';
        elBody.textContent  = (solusi && String(solusi).trim().length > 0)
            ? solusi
            : 'Solusi belum diisi pada data penyakit.';

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('.top3-row.clickable').forEach(row => {
        row.addEventListener('click', () => {
            openModal(row.dataset.kode, row.dataset.nama);
        });
    });

    btnClose?.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>

@endsection
