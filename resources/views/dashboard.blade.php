@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = Auth::user();
    $role = $role ?? ($user->role ?? 'peternak');

    $totalRiwayatSaya   = $totalRiwayatSaya   ?? 0;
    $diagnosaBulanIni   = $diagnosaBulanIni   ?? 0;
    $lastDiagnosaAt     = $lastDiagnosaAt     ?? null;

    $riwayatsTop = $riwayatsTop ?? collect();
    if (!($riwayatsTop instanceof \Illuminate\Support\Collection)) {
        $riwayatsTop = collect($riwayatsTop);
    }

    // hitung top penyakit (Top DS -> fallback CF)
    $counter = [];
    foreach ($riwayatsTop as $r) {
        $p = $r->payload ?? [];

        $ds0 = $p['ranking']['ds'][0] ?? null;
        $cf0 = $p['ranking']['cf'][0] ?? null;

        $top = is_array($ds0) ? $ds0 : (is_array($cf0) ? $cf0 : null);

        $kode = is_array($top) ? ($top['kode'] ?? $top['kode_penyakit'] ?? null) : null;
        $nama = is_array($top) ? ($top['nama'] ?? $top['nama_penyakit'] ?? $top['penyakit'] ?? null) : null;

        $kode = $kode ? strtoupper(trim($kode)) : null;
        $nama = $nama ? trim($nama) : null;

        if (!$kode && !$nama) continue;

        $key = $kode ?: $nama;
        if (!isset($counter[$key])) {
            $counter[$key] = [
                'kode' => $kode,
                'nama' => $nama ?? $kode,
                'jumlah' => 0
            ];
        }
        $counter[$key]['jumlah']++;
    }

    $topPenyakit = collect($counter)->sortByDesc('jumlah')->values()->take(5);

    $periodLabel = (request('start_date') || request('end_date'))
        ? ((request('start_date') ?? '-') . ' s/d ' . (request('end_date') ?? '-'))
        : 'Semua data';

    // label scope
    if ($role === 'peternak') {
        $scopeLabel = 'Berdasarkan riwayat kamu';
    } else {
        $scopeLabel = $selectedUserId ? 'Berdasarkan user terpilih' : 'Berdasarkan semua user';
    }
@endphp

<div class="page-header" style="margin-bottom:26px;">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Sistem Pakar Diagnosa Penyakit Ayam (CF & DS)</p>
</div>

<div class="hero-card">
    <div class="hero-left">
        <div class="hero-badge">
            <span class="hero-emoji">🐔</span>
            <span>Sistem Pakar</span>
        </div>

        <h2 class="hero-title">Selamat datang 👋</h2>
        <p class="hero-text">
            Sistem ini membantu mendiagnosa penyakit ayam menggunakan metode <b>Certainty Factor (CF)</b> dan
            <b>Dempster-Shafer (DS)</b> berdasarkan gejala yang dipilih.
        </p>

        <div class="hero-actions">
            <a href="{{ route('diagnosa.index') }}" class="btn btn-submit">
                <i class="fas fa-stethoscope"></i>
                Mulai Diagnosa
            </a>

            @if($role === 'peternak')
                <a href="{{ route('riwayat-diagnosa.index') }}" class="btn btn-outline">
                    <i class="fas fa-clock-rotate-left"></i>
                    Riwayat Diagnosa
                </a>
            @else
                <a href="{{ route('penyakit.index') }}" class="btn btn-outline">
                    <i class="fas fa-bacteria"></i>
                    Lihat Data Penyakit
                </a>
            @endif
        </div>
    </div>

    <div class="hero-right">
        <div class="hero-mini">
            <div class="mini-icon">🩺</div>
            <div class="mini-text">
                <div class="mini-title">Cepat</div>
                <div class="mini-sub">Pilih gejala & bobot CF</div>
            </div>
        </div>

        <div class="hero-mini">
            <div class="mini-icon">🧠</div>
            <div class="mini-text">
                <div class="mini-title">Akurat</div>
                <div class="mini-sub">CF & DS untuk tingkat keyakinan</div>
            </div>
        </div>

        <div class="hero-mini">
            <div class="mini-icon">📋</div>
            <div class="mini-text">
                <div class="mini-title">Rapi</div>
                <div class="mini-sub">Basis pengetahuan terstruktur</div>
            </div>
        </div>
    </div>
</div>

{{-- ===========================
     TOP PENYAKIT DI DASHBOARD
     =========================== --}}
<div class="card rekap-top" style="margin-top:18px;">
    <div class="card-head">
        <h3>Rekap Penyakit Terbanyak (Top Penyakit)</h3>
        <span class="badge badge-neutral">{{ $scopeLabel }}</span>
    </div>

    <div class="filter-wrap">
        <form id="filter-top-form" method="GET" action="{{ route('dashboard') }}" class="filter-form" novalidate>

            {{-- ✅ Dropdown user khusus pemilik/pakar --}}
            @if(in_array($role, ['pemilik','pakar']))
                <div class="filter-field">
                    <label>Pilih User</label>
                    <select name="user_id" id="user_id">
                        <option value="all" {{ !$selectedUserId ? 'selected' : '' }}>Semua</option>
                        @foreach(($userOptions ?? collect()) as $u)
                            <option value="{{ $u->id }}" {{ ($selectedUserId == $u->id) ? 'selected' : '' }}>
                                {{ $u->name }} {{ $u->role ? '('.$u->role.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="filter-field">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" id="start_date">
            </div>

            <div class="filter-field">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" id="end_date">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-primary">Tampilkan</button>

                @if(request('start_date') || request('end_date') || request('user_id'))
                    <a href="{{ route('dashboard') }}" class="btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        <div class="filter-meta">
            📊 Periode: <b>{{ $periodLabel }}</b>
            <span style="opacity:.7;">• Total data dihitung: <b>{{ $riwayatsTop->count() }}</b></span>
        </div>
    </div>

    <div class="divider"></div>

    @if($riwayatsTop->count() === 0)
        <div class="empty-state" style="padding:18px 0;">
            <div class="empty-ico">📈</div>
            <div class="empty-title">Belum ada data untuk periode ini.</div>
            <div class="empty-sub">Coba ubah periode / user, atau lakukan diagnosa terlebih dahulu.</div>
        </div>
    @else
        @if($topPenyakit->isEmpty())
            <p class="muted" style="margin:0;">Tidak ada data top penyakit yang bisa dihitung.</p>
        @else
            <div class="top-penyakit-wrap">
                @foreach($topPenyakit as $row)
                    <div class="top-penyakit-item">
                        <div class="tp-left">
                            <div class="tp-name">
                                {{ $row['kode'] ? $row['kode'].' — ' : '' }}{{ $row['nama'] }}
                            </div>
                            <div class="tp-sub">Jumlah kasus pada periode</div>
                        </div>
                        <div class="tp-right">
                            <span class="tp-count">{{ (int)$row['jumlah'] }}</span>
                            <span class="tp-label">kasus</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>

{{-- ✅ SweetAlert2 untuk validasi periode --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ===============================
   REKAP TOP PENYAKIT - SPACING
================================ */
.card.rekap-top{padding:22px 24px;margin-bottom:24px;}
.card.rekap-top .card-head{margin-bottom:16px;}
.card.rekap-top .filter-wrap{margin-top:6px;margin-bottom:16px;}
.card.rekap-top .filter-form{gap:14px;}
.card.rekap-top .filter-actions{gap:10px;}
.card.rekap-top .filter-meta{margin-top:12px;font-size:13px;}
.card.rekap-top .divider{margin:18px 0;}
.card.rekap-top .top-penyakit-wrap{display:grid;gap:14px;}
.card.rekap-top .top-penyakit-item{
    display:flex;align-items:center;justify-content:space-between;gap:14px;
    padding:16px 18px;border:1px solid rgba(0,0,0,.08);border-radius:16px;background:#fff;
}
.card.rekap-top .tp-name{font-size:15px;font-weight:800;line-height:1.25;margin-bottom:4px;}
.card.rekap-top .tp-sub{font-size:12px;opacity:.7;}
.card.rekap-top .tp-right{display:flex;align-items:baseline;gap:6px;white-space:nowrap;}
.card.rekap-top .tp-count{font-size:24px;font-weight:900;}
.card.rekap-top .tp-label{font-size:12px;opacity:.7;font-weight:700;}

/* bikin select nyatu dengan input (kalau theme kamu belum styling) */
.filter-field select{
    width:100%;
    padding: 10px 12px;
    border: 1px solid rgba(0,0,0,.12);
    border-radius: 12px;
    outline: none;
    background: #fff;
}

/* SweetAlert */
.swal2-popup.swal-yellow{width:360px !important;padding:18px 20px !important;border-radius:14px;border:2px solid #f6c453;box-shadow:0 12px 35px rgba(0,0,0,.18);}
.swal2-popup.swal-yellow .swal2-icon{width:56px !important;height:56px !important;margin:12px auto 8px !important;}
.swal2-popup.swal-yellow .swal2-icon.swal2-warning{border-color:#f6c453 !important;color:#f6c453 !important;}
.swal2-popup.swal-yellow .swal2-title{font-size:18px !important;font-weight:700;margin:6px 0 4px !important;color:#3a2a00;}
.swal2-popup.swal-yellow .swal2-html-container{font-size:14px !important;line-height:1.4;margin:4px 0 10px !important;color:#5a4300;}
.swal2-popup.swal-yellow .swal2-confirm.btn-yellow{font-size:13px !important;padding:8px 16px !important;border-radius:10px !important;background:#f6c453 !important;color:#3a2a00 !important;border:0 !important;font-weight:700 !important;box-shadow:0 6px 16px rgba(246,196,83,.35);}

@media (max-width: 640px){
    .card.rekap-top{padding:18px 16px;}
    .card.rekap-top .top-penyakit-item{padding:14px 14px;}
    .card.rekap-top .tp-count{font-size:20px;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form  = document.getElementById('filter-top-form');
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
        const s = start.value;
        const t = end.value;

        if (!s || !t) return;

        if (s > t) {
            e.preventDefault();
            swalWarn('Periode Tidak Valid', '"Dari Tanggal" harus lebih kecil / sama dengan "Sampai Tanggal".');
            start.focus();
        }
    });
});
</script>

{{-- STATS: beda untuk peternak vs pakar/pemilik --}}
@if($role === 'peternak')
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Riwayat Diagnosa Saya</div>
                <div class="stat-number">{{ $totalRiwayatSaya }}</div>
            </div>
            <div class="stat-icon">📒</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Diagnosa Bulan Ini</div>
                <div class="stat-number">{{ $diagnosaBulanIni }}</div>
            </div>
            <div class="stat-icon">🗓️</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Terakhir Diagnosa</div>
                <div class="stat-number" style="font-size:14px; line-height:1.3;">
                    @if($lastDiagnosaAt)
                        {{ \Carbon\Carbon::parse($lastDiagnosaAt)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="stat-icon">⏱️</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🩺</div>
                <div class="info-title">Mulai Diagnosa</div>
            </div>
            <p class="info-text">
                Pilih gejala yang terlihat pada ayam, tentukan bobot keyakinan (CF), lalu sistem akan menampilkan hasil peringkat CF & DS.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🧾</div>
                <div class="info-title">Riwayat Diagnosa</div>
            </div>
            <p class="info-text">
                Semua hasil diagnosa yang pernah kamu lakukan tersimpan otomatis (tanggal, gejala yang dipilih, dan ranking penyakit).
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">✅</div>
                <div class="info-title">Saran Penanganan</div>
            </div>
            <p class="info-text">
                Gunakan saran penanganan awal sebagai panduan cepat. Untuk kasus berat/menular, tetap konfirmasi ke dokter hewan/pakar.
            </p>
        </div>
    </div>
@else
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Gejala</div>
                <div class="stat-number">{{ $totalGejala ?? 0 }}</div>
            </div>
            <div class="stat-icon">⚙️</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Penyakit</div>
                <div class="stat-number">{{ $totalPenyakit ?? 0 }}</div>
            </div>
            <div class="stat-icon">🦠</div>
        </div>

        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-title">Total Rule</div>
                <div class="stat-number">{{ $totalRule ?? 0 }}</div>
            </div>
            <div class="stat-icon">📌</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🔍</div>
                <div class="info-title">Data Gejala</div>
            </div>
            <p class="info-text">
                Kelola daftar gejala penyakit ayam untuk proses diagnosa. Setiap gejala memiliki kode unik untuk identifikasi.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🦠</div>
                <div class="info-title">Data Penyakit</div>
            </div>
            <p class="info-text">
                Database penyakit ayam beserta solusi penanganannya. Menjadi rujukan utama pada hasil diagnosa.
            </p>
        </div>

        <div class="info-card">
            <div class="info-head">
                <div class="info-icon">🧠</div>
                <div class="info-title">Basis Pengetahuan</div>
            </div>
            <p class="info-text">
                Aturan relasi gejala–penyakit dengan bobot CF/DS sebagai inti penalaran sistem dalam memberikan diagnosa.
            </p>
        </div>
    </div>
@endif
@endsection
