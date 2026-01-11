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

    // ✅ solusiMap dari controller: [kode_penyakit => solusi]
    $solusiMap = $solusiMap ?? [];
    $getSolusi = fn($kode) => $kode ? ($solusiMap[$kode] ?? null) : null;
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
                        @php
                            $kode = $row['kode'] ?? null;
                            $nama = $row['nama'] ?? '-';
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
                                    <span class="code">{{ $row['kode'] ?? '-' }}</span>
                                    <span class="name">{{ $row['nama'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($row['nilai'] ?? null) }}</span>
                                <span class="pct">{{ number_format((float)($row['persen'] ?? 0), 2) }}%</span>
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
                            $kode = $row['kode'] ?? null;
                            $nama = $row['nama'] ?? '-';
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
                                    <span class="code">{{ $row['kode'] ?? '-' }}</span>
                                    <span class="name">{{ $row['nama'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="top3-right">
                                <span class="val">{{ $fmtVal($row['nilai'] ?? null) }}</span>
                                <span class="pct">{{ number_format((float)($row['persen'] ?? 0), 2) }}%</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL SOLUSI --}}
<div id="solusiModal" class="modal-backdrop" hidden>
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
<script>
(function(){
    const modal = document.getElementById('solusiModal');
    const btnClose = document.getElementById('solusiClose');
    const elKode = document.getElementById('solusiKode');
    const elTitle = document.getElementById('solusiTitle');
    const elBody = document.getElementById('solusiBody');

    function openModal(kode, nama, solusi){
        elKode.textContent = kode ? kode : '-';
        elTitle.textContent = nama ? ('Solusi Penanganan — ' + nama) : 'Solusi Penanganan';
        elBody.textContent = solusi && solusi.trim() ? solusi : 'Solusi belum diisi pada data penyakit.';
        modal.hidden = false;
    }
    function closeModal(){
        modal.hidden = true;
    }

    document.querySelectorAll('.top3-row[data-solusi]').forEach(el => {
        el.addEventListener('click', () => {
            openModal(el.dataset.kode, el.dataset.nama, el.dataset.solusi);
        });
    });

    btnClose?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => { if(e.target === modal) closeModal(); });
    document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && !modal.hidden) closeModal(); });
})();
</script>

@endsection
