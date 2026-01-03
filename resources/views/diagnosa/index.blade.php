@extends('layouts.app')

@section('title', 'Diagnosa Penyakit')

@section('content')

<div class="page-header" style="margin-bottom:30px;">
    <h1 class="page-title">Diagnosa Penyakit Ayam</h1>
    <p class="page-subtitle">
        Pilih gejala yang dialami ayam, lalu tentukan tingkat keyakinan (bobot CF) untuk setiap gejala.
    </p>
</div>

<div class="stats-banner">
    <div class="stat-card">
        <div class="stat-number" id="total-gejala">{{ count($gejalas) }}</div>
        <div class="stat-label">Total Gejala</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="selected-count">0</div>
        <div class="stat-label">Gejala Dipilih</div>
    </div>
</div>

<div class="form-card">

    <div class="info-box">
        <div class="info-icon">💡</div>
        <div class="info-box-text">
            <b>Cara Menggunakan:</b> Centang gejala yang dialami ayam, lalu klik bobot CF di sampingnya.
            Minimal pilih <b>1 gejala</b> yang punya bobot CF untuk melakukan diagnosa.
        </div>
    </div>

    {{-- Toast --}}
    <div class="toast" id="toast" style="display:none;">
        <div class="toast-icon" id="toast-icon">⚠️</div>
        <div class="toast-text" id="toast-text">Pesan</div>
        <button type="button" class="toast-x" id="toast-x">✕</button>
    </div>

    <form action="{{ route('diagnosa.hasil') }}" method="POST" id="diagnosa-form">
        @csrf

        <div class="form-group">
            <div class="form-head">
                <label class="form-label">
                    <i class="fas fa-stethoscope"></i>
                    Pilih Gejala & Bobot CF
                    <span class="required-mark">*</span>
                </label>

                <div class="hint-pill">
                    <i class="fas fa-hand-pointer"></i>
                    Centang gejala → klik bobot CF
                </div>
            </div>

            {{-- Search gejala --}}
            <div class="search-wrap">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="gejala-search" placeholder="Cari gejala (kode / nama)..." autocomplete="off">
                    <button type="button" class="search-clear" id="gejala-search-clear" title="Hapus">✕</button>
                </div>
                <div class="search-meta" id="gejala-search-meta">Menampilkan semua gejala</div>
            </div>

            <div class="gejala-container">
                @foreach($gejalas as $g)
                    <div class="gejala-row" data-row="{{ $g->id }}">
                        <label class="checkbox-wrapper">
                            <input type="checkbox"
                                   name="gejala_id[]"
                                   value="{{ $g->id }}"
                                   class="cb-gejala">
                            <div class="gejala-text">
                                <span class="gejala-code">{{ $g->kode_gejala }}</span>
                                <span class="gejala-name">{{ $g->nama_gejala }}</span>
                            </div>
                        </label>

                        <input type="hidden" name="cf_user[{{ $g->id }}]" class="cf-hidden" value="">

                        <div class="cf-buttons" aria-label="Pilih bobot CF" data-gejala="{{ $g->id }}">
                            <button type="button" class="cf-btn" data-value="0">Tidak</button>
                            <button type="button" class="cf-btn" data-value="0.2">Sedikit Yakin</button>
                            <button type="button" class="cf-btn" data-value="0.4">Cukup Yakin</button>
                            <button type="button" class="cf-btn" data-value="0.6">Hampir Yakin</button>
                            <button type="button" class="cf-btn" data-value="0.8">Yakin</button>
                            <button type="button" class="cf-btn" data-value="1">Sangat Yakin</button>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('gejala_id')
                <div class="error-message">
                    ⚠️ {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Bottom Bar --}}
        <div class="bottom-bar" id="bottom-bar">
            <div class="bottom-bar-inner">
                <div class="bb-left">
                    <div class="bb-title">Siap proses diagnosa?</div>
                    <div class="bb-sub" id="bottom-subtext">Pilih minimal 1 gejala & isi bobot CF.</div>

                    <div class="bb-progress">
                        <div class="bb-bar">
                            <div class="bb-fill" id="fp-bar-fill" style="width:0%"></div>
                        </div>
                        <div class="bb-meta">
                            <span><b id="fp-selected">0</b> dipilih</span>
                            <span class="dot">•</span>
                            <span><b id="fp-filled">0</b> bobot</span>
                            <span class="dot">•</span>
                            <span class="bb-badge" id="fp-percent">0%</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit bottom-submit" id="btn-submit-sticky">
                    <i class="fas fa-microscope"></i>
                    Proses Diagnosa
                </button>
            </div>
        </div>
        {{-- End Bottom Bar --}}
    </form>
</div>

<style>
    :root{ --sidebar-w: 260px; }

    .stats-banner{ display:flex; gap:14px; margin-bottom:18px; max-width:1800px; margin-left:auto; margin-right:auto; }
    .stat-card{ flex:1; background:#fff; border:1px solid #fde68a; border-radius:14px; padding:14px 18px; box-shadow:0 4px 16px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .stat-number{ font-size:26px; font-weight:900; color:#111827; line-height:1; }
    .stat-label{ font-size:13px; color:#111827; opacity:.75; font-weight:800; text-align:right; }

    .form-card{
        background:#fff; border-radius:16px; padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08); border:1px solid #fde68a;
        max-width:1800px; margin:0 auto; font-size:14px; color:#374151;
        padding-bottom: 170px;
        position: relative;
    }

    .form-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:16px; flex-wrap:wrap; }
    .form-label{ margin:0; font-size:18px; font-weight:900; color:#111827; display:flex; align-items:center; gap:10px; }
    .form-label i{ color:#f59e0b; }
    .required-mark{ color:#ef4444; margin-left:4px; font-weight:900; }

    .hint-pill{ display:inline-flex; align-items:center; gap:8px; padding:10px 12px; border-radius:999px; background:#fffbeb; border:1px solid #fde68a; color:#111827; font-weight:800; font-size:13px; white-space:nowrap; }
    .hint-pill i{ color:#f59e0b; }

    .info-box{ background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:16px 18px; margin-bottom:18px; display:flex; align-items:flex-start; gap:12px; }
    .info-icon{ width:36px;height:36px;border-radius:12px; background:#fff9c4; border:1px solid #fde68a; display:flex;align-items:center;justify-content:center; font-size:18px;flex-shrink:0; }
    .info-box-text{ font-size:14px; color:#111827; opacity:.8; line-height:1.6; font-weight:600; }

    .gejala-container{ display:flex; flex-direction:column; gap:10px; }
    .gejala-row{
        display:flex; align-items:center; justify-content:space-between; gap:14px;
        padding:14px 16px; background:#fffef5; border:1px solid #fde68a;
        border-radius:14px; transition:all .18s ease;
    }
    .gejala-row:hover{ background:#fff9c4; transform: translateY(-1px); box-shadow:0 6px 18px rgba(245,158,11,0.12); }
    .gejala-row.active{ background:#fffbeb; border-color:#f59e0b; box-shadow:0 8px 22px rgba(245,158,11,0.18); }

    .checkbox-wrapper{ display:flex; align-items:center; gap:12px; flex:1; margin:0; cursor:pointer; user-select:none; min-width:0; }
    .cb-gejala{ width:20px;height:20px; cursor:pointer; accent-color:#f59e0b; flex-shrink:0; margin-top:0; }

    .gejala-text{ display:flex; align-items:center; gap:12px; line-height:1.4; white-space:nowrap; }
    .gejala-code{ display:inline-flex; align-items:center; padding:3px 8px; border-radius:999px; background:#fff; border:1px solid #fde68a; color:#111827; font-weight:900; font-size:11px; white-space:nowrap; letter-spacing:0.3px; flex-shrink:0; }
    .gejala-name{ font-size:15px; color:#111827; font-weight:400; line-height:1.35; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }

    .cf-buttons{ display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end; min-width:260px; opacity:.55; pointer-events:none; transition:opacity .15s ease; }
    .gejala-row.active .cf-buttons{ opacity:1; pointer-events:auto; }

    .cf-btn{ border:1.8px solid #fde68a; background:#fff; color:#111827; font-weight:900; font-size:13px; padding:10px 12px; border-radius:12px; cursor:pointer; min-width:44px; text-align:center; transition:all .15s ease; }
    .cf-btn:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .cf-btn.selected{ background:#f59e0b; border-color:#f59e0b; color:#fff; box-shadow:0 8px 20px rgba(245,158,11,0.25); }
    .cf-btn.need{ border-color:#ef4444 !important; box-shadow:0 0 0 4px rgba(239,68,68,0.12) !important; }

    .btn{ padding:12px 18px; border:none; border-radius:12px; font-size:14px; font-weight:900; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:10px; transition:all .2s ease; white-space:nowrap; }
    .btn-submit{ background:#f59e0b; color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); letter-spacing:0.3px; }
    .btn-submit:hover{ background:#d97706; transform: translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }

    .error-message{ display:flex; align-items:center; gap:10px; color:#7f1d1d; background:#fef2f2; padding:12px 14px; border-radius:12px; margin-top:14px; font-size:14px; border:1px solid #fecaca; font-weight:800; }

    .bottom-bar{
        position: fixed; bottom: 0; left: var(--sidebar-w); width: calc(100% - var(--sidebar-w));
        z-index: 9999; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
        border-top: 1px solid #fde68a; padding: 10px 14px; box-sizing: border-box;
    }
    .bottom-bar-inner{ max-width: 1800px; margin: 0 auto; display:flex; align-items:center; justify-content:space-between; gap:14px; }
    .bb-left{ min-width:0; flex:1; }
    .bb-title{ font-size:13px; font-weight:800; color:#111827; line-height:1.2; }
    .bb-sub{ margin-top:2px; font-size:12px; font-weight:600; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:900px; }
    .bb-progress{ margin-top:8px; display:flex; align-items:center; gap:10px; }
    .bb-bar{ height:8px; flex:1; min-width:160px; background:#fff7ed; border:1px solid #fde68a; border-radius:999px; overflow:hidden; }
    .bb-fill{ height:100%; width:0%; background:#f59e0b; border-radius:999px; transition:width .18s ease; }
    .bb-meta{ display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; color:#111827; opacity:.9; white-space:nowrap; }
    .bb-meta .dot{ opacity:.6; }
    .bb-badge{ display:inline-flex; padding:4px 8px; border-radius:999px; background:#fffbeb; border:1px solid #fde68a; font-weight:800; }
    .bottom-submit{ padding:11px 16px; border-radius:14px; box-shadow:0 10px 24px rgba(0,0,0,0.12); width:auto; flex-shrink:0; }

    @media (max-width: 1024px){ .bottom-bar{ left:0; width:100%; } }
    @media (max-width: 768px){
        .form-card{ padding:24px; padding-bottom: 220px; }
        .stats-banner{ flex-direction:column; }
        .gejala-row{ flex-direction:column; align-items:stretch; }
        .cf-buttons{ min-width:100%; justify-content:flex-start; }
        .gejala-text{ flex-wrap:wrap; }
        .gejala-name{ white-space:normal; }
        .bottom-bar-inner{ flex-direction:column; align-items:stretch; }
        .bb-sub{ max-width:100%; white-space:normal; }
        .bb-progress{ flex-direction:column; align-items:stretch; }
        .bb-meta{ justify-content:space-between; width:100%; }
        .bottom-submit{ width:100%; justify-content:center; }
    }

    /* Search */
    .search-wrap{ margin: 8px 0 14px; }
    .search-box{ display:flex; align-items:center; gap:10px; padding:12px 14px; border:1px solid #fde68a; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(0,0,0,0.04); }
    .search-box i{ color:#f59e0b; font-size:14px; }
    .search-box input{ flex:1; border:none; outline:none; font-size:14px; font-weight:700; color:#111827; background:transparent; }
    .search-box input::placeholder{ color:#9ca3af; font-weight:700; }
    .search-clear{ border:1px solid #fde68a; background:#fffbeb; color:#111827; width:36px; height:36px; border-radius:12px; cursor:pointer; font-weight:900; display:inline-flex; align-items:center; justify-content:center; transition:all .15s ease; }
    .search-clear:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .search-meta{ margin-top:8px; font-size:12px; font-weight:700; color:#6b7280; }

    /* Toast */
    .toast{
        position: sticky;
        top: 12px;
        z-index: 50;
        display:flex;
        align-items:center;
        gap:10px;
        padding:12px 14px;
        margin-bottom:14px;
        border-radius:14px;
        border:1px solid #fecaca;
        background:#fef2f2;
        color:#7f1d1d;
        font-weight:800;
        box-shadow:0 10px 26px rgba(0,0,0,0.08);
    }
    .toast.ok{
        border-color:#86efac;
        background:#ecfdf5;
        color:#065f46;
    }
    .toast-icon{ width:28px;height:28px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#fff; border:1px solid rgba(0,0,0,0.06); flex-shrink:0; }
    .toast-text{ flex:1; min-width:0; }
    .toast-x{
        border:1px solid rgba(0,0,0,0.08);
        background:#fff;
        width:34px;height:34px;
        border-radius:12px;
        cursor:pointer;
        font-weight:900;
        display:flex; align-items:center; justify-content:center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('diagnosa-form');
    const selectedCountEl = document.getElementById('selected-count');

    const fpSelectedEl = document.getElementById('fp-selected');
    const fpFilledEl   = document.getElementById('fp-filled');
    const fpPercentEl  = document.getElementById('fp-percent');
    const fpBarFillEl  = document.getElementById('fp-bar-fill');

    // Toast
    const toast    = document.getElementById('toast');
    const toastTxt = document.getElementById('toast-text');
    const toastX   = document.getElementById('toast-x');
    let toastTimer = null;

    function showToast(msg, type='warn'){
        if (!toast) return;
        toast.style.display = 'flex';
        toast.classList.toggle('ok', type === 'ok');
        if (toastTxt) toastTxt.textContent = msg;

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toast.style.display = 'none'; }, 2600);
    }
    if (toastX){
        toastX.addEventListener('click', () => {
            toast.style.display = 'none';
            if (toastTimer) clearTimeout(toastTimer);
        });
    }

    // ===== Search =====
    const searchInput = document.getElementById('gejala-search');
    const searchClear = document.getElementById('gejala-search-clear');
    const searchMeta  = document.getElementById('gejala-search-meta');
    const totalGejalaEl = document.getElementById('total-gejala');

    function normalizeText(s){ return (s || '').toString().toLowerCase().trim(); }

    function applySearch(){
        const q = normalizeText(searchInput?.value);
        const rows = Array.from(document.querySelectorAll('.gejala-row'));
        let shown = 0;

        rows.forEach(row => {
            const code = normalizeText(row.querySelector('.gejala-code')?.innerText);
            const name = normalizeText(row.querySelector('.gejala-name')?.innerText);
            const match = (q === '') || code.includes(q) || name.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });

        if (totalGejalaEl) totalGejalaEl.textContent = shown;

        if (searchMeta){
            if (q === '') searchMeta.textContent = 'Menampilkan semua gejala';
            else searchMeta.textContent = `Hasil: ${shown} gejala untuk "${searchInput.value}"`;
        }

        if (searchClear){
            searchClear.style.display = (q === '') ? 'none' : 'inline-flex';
        }
    }

    if (searchInput) searchInput.addEventListener('input', applySearch);
    if (searchClear){
        searchClear.addEventListener('click', () => {
            if (!searchInput) return;
            searchInput.value = '';
            applySearch();
            searchInput.focus();
        });
    }
    applySearch();

    function getCheckedRows() {
        return Array.from(document.querySelectorAll('.cb-gejala:checked'))
            .map(cb => cb.closest('.gejala-row'));
    }

    function updateProgress() {
        const checkedRows = getCheckedRows();
        const selected = checkedRows.length;

        let filled = 0;
        checkedRows.forEach(row => {
            const hidden = row.querySelector('.cf-hidden');
            // dianggap terisi kalau hidden.value bukan kosong
            if (hidden && hidden.value !== '') filled++;
        });

        selectedCountEl.textContent = selected;
        selectedCountEl.style.color = selected > 0 ? '#16a34a' : '#78350f';

        fpSelectedEl.textContent = selected;
        fpFilledEl.textContent = filled;

        let percent = 0;
        if (selected > 0) percent = Math.round((filled / selected) * 100);
        fpPercentEl.textContent = percent + '%';
        fpBarFillEl.style.width = percent + '%';

        const sub = document.getElementById('bottom-subtext');
        if (sub) {
            if (selected === 0) sub.textContent = 'Pilih minimal 1 gejala & isi bobot CF.';
            else if (filled < selected) sub.textContent = `Isi bobot untuk ${selected - filled} gejala lagi.`;
            else sub.textContent = 'Semua bobot terisi ✅ Klik Proses Diagnosa.';
        }
    }

    // Checkbox toggle
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('cb-gejala')) return;

        const row = e.target.closest('.gejala-row');
        const hidden = row.querySelector('.cf-hidden');
        const btns = row.querySelectorAll('.cf-btn');

        if (e.target.checked) {
            row.classList.add('active');
        } else {
            row.classList.remove('active');
            if (hidden) hidden.value = '';
            btns.forEach(b => b.classList.remove('selected', 'need'));
        }

        updateProgress();
    });

    // CF button click
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('cf-btn')) return;

        const btn = e.target;
        const row = btn.closest('.gejala-row');
        const cb  = row.querySelector('.cb-gejala');

        if (!cb.checked) {
            cb.checked = true;
            row.classList.add('active');
        }

        const value = btn.getAttribute('data-value');
        const hidden = row.querySelector('.cf-hidden');
        if (hidden) hidden.value = value;

        row.querySelectorAll('.cf-btn').forEach(b => b.classList.remove('selected', 'need'));
        btn.classList.add('selected');

        updateProgress();
    });

    // Submit intercept: minimal 1 gejala yang punya bobot (value terisi dan bukan kosong)
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const checkedRows = getCheckedRows();

        // 1) minimal ada yang dicentang
        if (checkedRows.length === 0) {
            showToast('Silakan pilih minimal 1 gejala.', 'warn');
            return;
        }

        // 2) minimal ada 1 yang bobotnya terisi (dan tidak kosong)
        // kamu mau "harus pilih 1 yang memiliki bobot" -> ini inti fix nya
        const rowsWithValue = checkedRows.filter(row => {
            const v = row.querySelector('.cf-hidden')?.value;
            return (v !== undefined && v !== null && v !== '');
        });

        if (rowsWithValue.length === 0) {
            showToast('Pilih minimal 1 gejala lalu tentukan bobot CF-nya.', 'warn');
            // kasih highlight tombol CF di baris yang dicentang
            checkedRows.forEach(row => {
                row.querySelectorAll('.cf-btn').forEach(b => b.classList.add('need'));
                setTimeout(() => row.querySelectorAll('.cf-btn').forEach(b => b.classList.remove('need')), 1400);
            });
            return;
        }

        // 3) kalau kamu mau: SEMUA yang dicentang wajib punya bobot
        // kalau tidak mau, comment blok ini
        let okAll = true;
        checkedRows.forEach(row => {
            const hidden = row.querySelector('.cf-hidden');
            if (!hidden || hidden.value === '') {
                okAll = false;
                row.querySelectorAll('.cf-btn').forEach(b => b.classList.add('need'));
                setTimeout(() => row.querySelectorAll('.cf-btn').forEach(b => b.classList.remove('need')), 1400);
            }
        });
        if (!okAll) {
            showToast('Silakan pilih bobot CF untuk semua gejala yang dicentang.', 'warn');
            return;
        }

        // submit beneran
        form.submit();
    });

    updateProgress();
});
</script>

@endsection
