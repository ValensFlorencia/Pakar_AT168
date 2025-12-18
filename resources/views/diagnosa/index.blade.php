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
            Minimal pilih <b>1 gejala</b> untuk melakukan diagnosa.
        </div>
    </div>

    {{-- Modal Konfirmasi Diagnosa --}}
    <div class="confirm-overlay" id="confirm-overlay" style="display:none;">
        <div class="confirm-modal">
            <div class="confirm-head">
                <div>
                    <div class="confirm-title">Konfirmasi Diagnosa</div>
                    <div class="confirm-sub" id="confirm-sub">0 gejala dipilih</div>
                </div>
                <button type="button" class="confirm-x" id="confirm-close">✕</button>
            </div>

            <div class="confirm-body">
                <div class="confirm-info">
                    Pastikan gejala & bobot CF sudah benar. Klik item untuk kembali ke gejala tersebut.
                </div>

                <div class="confirm-list" id="confirm-list">
                    {{-- diisi via JS --}}
                </div>
            </div>

            <div class="confirm-actions">
                <button type="button" class="confirm-btn ghost" id="confirm-back">Kembali</button>
                <button type="button" class="confirm-btn primary" id="confirm-go">Diagnosa</button>
            </div>
        </div>
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
                            <button type="button" class="cf-btn" data-value="0">0</button>
                            <button type="button" class="cf-btn" data-value="0.2">0.2</button>
                            <button type="button" class="cf-btn" data-value="0.4">0.4</button>
                            <button type="button" class="cf-btn" data-value="0.6">0.6</button>
                            <button type="button" class="cf-btn" data-value="0.8">0.8</button>
                            <button type="button" class="cf-btn" data-value="1">1</button>
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

        {{-- ===== Bottom Bar (rapi) ===== --}}
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
        {{-- ===== End Bottom Bar ===== --}}

    </form>
</div>

<style>
    :root{
        --sidebar-w: 260px; /* ubah kalau sidebar beda */
    }

    /* Stats */
    .stats-banner{
        display:flex; gap:14px; margin-bottom:18px;
        max-width:1800px; margin-left:auto; margin-right:auto;
    }
    .stat-card{
        flex:1; background:#fff; border:1px solid #fde68a; border-radius:14px;
        padding:14px 18px; box-shadow:0 4px 16px rgba(0,0,0,0.05);
        display:flex; align-items:center; justify-content:space-between; gap:10px;
    }
    .stat-number{ font-size:26px; font-weight:900; color:#111827; line-height:1; }
    .stat-label{ font-size:13px; color:#111827; opacity:.75; font-weight:800; text-align:right; }

    /* Form Card */
    .form-card{
        background:#fff; border-radius:16px; padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08); border:1px solid #fde68a;
        max-width:1800px; margin:0 auto; font-size:14px; color:#374151;

        /* ✅ biar konten nggak ketutup bottom bar */
        padding-bottom: 170px;
    }

    .form-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:16px; flex-wrap:wrap; }
    .form-label{ margin:0; font-size:18px; font-weight:900; color:#111827; display:flex; align-items:center; gap:10px; }
    .form-label i{ color:#f59e0b; }
    .required-mark{ color:#ef4444; margin-left:4px; font-weight:900; }

    .hint-pill{
        display:inline-flex; align-items:center; gap:8px; padding:10px 12px;
        border-radius:999px; background:#fffbeb; border:1px solid #fde68a;
        color:#111827; font-weight:800; font-size:13px; white-space:nowrap;
    }
    .hint-pill i{ color:#f59e0b; }

    .info-box{
        background:#fffbeb; border:1px solid #fde68a; border-radius:14px;
        padding:16px 18px; margin-bottom:18px; display:flex; align-items:flex-start; gap:12px;
    }
    .info-icon{
        width:36px;height:36px;border-radius:12px; background:#fff9c4; border:1px solid #fde68a;
        display:flex;align-items:center;justify-content:center; font-size:18px;flex-shrink:0;
    }
    .info-box-text{ font-size:14px; color:#111827; opacity:.8; line-height:1.6; font-weight:600; }

    /* Gejala list */
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
    .gejala-code{
        display:inline-flex; align-items:center; padding:3px 8px; border-radius:999px;
        background:#fff; border:1px solid #fde68a; color:#111827; font-weight:900;
        font-size:11px; white-space:nowrap; letter-spacing:0.3px; flex-shrink:0;
    }
    .gejala-name{
        font-size:15px; color:#111827; font-weight:400; line-height:1.35;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0;
    }

    /* CF buttons */
    .cf-buttons{
        display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;
        min-width:260px; opacity:.55; pointer-events:none; transition:opacity .15s ease;
    }
    .gejala-row.active .cf-buttons{ opacity:1; pointer-events:auto; }

    .cf-btn{
        border:1.8px solid #fde68a; background:#fff; color:#111827;
        font-weight:900; font-size:13px; padding:10px 12px; border-radius:12px;
        cursor:pointer; min-width:44px; text-align:center; transition:all .15s ease;
    }
    .cf-btn:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .cf-btn.selected{ background:#f59e0b; border-color:#f59e0b; color:#fff; box-shadow:0 8px 20px rgba(245,158,11,0.25); }
    .cf-btn.need{ border-color:#ef4444 !important; box-shadow:0 0 0 4px rgba(239,68,68,0.12) !important; }

    /* Button */
    .btn{
        padding:12px 18px; border:none; border-radius:12px;
        font-size:14px; font-weight:900; cursor:pointer; text-decoration:none;
        display:inline-flex; align-items:center; gap:10px; transition:all .2s ease;
        white-space:nowrap;
    }
    .btn-submit{ background:#f59e0b; color:#fff; box-shadow:0 4px 12px rgba(245,158,11,0.25); letter-spacing:0.3px; }
    .btn-submit:hover{ background:#d97706; transform: translateY(-1px); box-shadow:0 6px 16px rgba(245,158,11,0.35); }

    /* Error */
    .error-message{
        display:flex; align-items:center; gap:10px; color:#7f1d1d; background:#fef2f2;
        padding:12px 14px; border-radius:12px; margin-top:14px; font-size:14px;
        border:1px solid #fecaca; font-weight:800;
    }

    /* ===== Bottom Bar (FIXED + rapi) ===== */
    .bottom-bar{
        position: fixed;
        bottom: 0;
        left: var(--sidebar-w);
        width: calc(100% - var(--sidebar-w));
        z-index: 9999;

        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        border-top: 1px solid #fde68a;
        padding: 10px 14px;
        box-sizing: border-box;
    }

    .bottom-bar-inner{
        max-width: 1800px;
        margin: 0 auto;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
    }

    .bb-left{ min-width:0; flex:1; }
    .bb-title{ font-size:13px; font-weight:800; color:#111827; line-height:1.2; }
    .bb-sub{
        margin-top:2px; font-size:12px; font-weight:600; color:#6b7280;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:900px;
    }

    .bb-progress{
        margin-top:8px;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .bb-bar{
        height:8px;
        flex:1;
        min-width:160px;
        background:#fff7ed;
        border:1px solid #fde68a;
        border-radius:999px;
        overflow:hidden;
    }
    .bb-fill{
        height:100%;
        width:0%;
        background:#f59e0b;
        border-radius:999px;
        transition:width .18s ease;
    }
    .bb-meta{
        display:inline-flex;
        align-items:center;
        gap:8px;
        font-size:12px;
        font-weight:700;
        color:#111827;
        opacity:.9;
        white-space:nowrap;
    }
    .bb-meta .dot{ opacity:.6; }
    .bb-badge{
        display:inline-flex;
        padding:4px 8px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        font-weight:800;
    }

    .bottom-submit{
        padding:11px 16px;
        border-radius:14px;
        box-shadow:0 10px 24px rgba(0,0,0,0.12);
        width:auto; /* ✅ jangan full */
        flex-shrink:0;
    }

    /* Responsive */
    @media (max-width: 1024px){
        .bottom-bar{ left:0; width:100%; }
    }
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

    /* Global tone */
    .hint-pill, .gejala-code, .confirm-sub, .confirm-code, .confirm-cf{ font-weight:700 !important; }

    /* ===== Modal Konfirmasi (punyamu tetap) ===== */
    .confirm-overlay{
        position:fixed;
        inset:0;
        background:rgba(17,24,39,0.45);
        z-index:99999;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:22px;
        backdrop-filter: blur(4px);
    }
    .confirm-modal{
        width:min(760px, 96vw);
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:22px;
        box-shadow:0 26px 80px rgba(0,0,0,0.22);
        overflow:hidden;
    }
    .confirm-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:12px;
        padding:18px 20px;
        background:linear-gradient(180deg,#fffbeb 0%, #ffffff 100%);
        border-bottom:1px solid #fde68a;
    }
    .confirm-title{ font-size:18px; font-weight:900; color:#111827; letter-spacing:-0.2px; }
    .confirm-sub{
        margin-top:6px;
        display:inline-flex;
        align-items:center;
        gap:8px;
        font-size:12px;
        font-weight:900;
        color:#111827;
        background:#fffbeb;
        border:1px solid #fde68a;
        padding:6px 10px;
        border-radius:999px;
    }
    .confirm-x{
        border:none;
        background:#ffffff;
        border:1px solid #fde68a;
        color:#111827;
        width:40px;height:40px;
        border-radius:14px;
        cursor:pointer;
        font-weight:900;
        display:flex;
        align-items:center;
        justify-content:center;
        transition:all .15s ease;
    }
    .confirm-x:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .confirm-body{ padding:16px 20px 10px; }
    .confirm-info{
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:16px;
        padding:12px 14px;
        color:#111827;
        font-weight:800;
        font-size:13px;
        margin-bottom:14px;
        opacity:.8;
    }
    .confirm-list{ max-height:320px; overflow:auto; padding-right:6px; }
    .confirm-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        padding:14px 14px;
        border:1px solid #fde68a;
        background:#ffffff;
        border-radius:18px;
        margin-bottom:12px;
        cursor:pointer;
        transition:all .15s ease;
    }
    .confirm-item:hover{ border-color:#f59e0b; box-shadow:0 10px 26px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .confirm-main{ display:flex; align-items:center; gap:12px; min-width:0; }
    .confirm-code{
        display:inline-flex;
        width:max-content;
        padding:5px 10px;
        border-radius:999px;
        border:1px solid #fde68a;
        background:#fffbeb;
        color:#111827;
        font-weight:900;
        font-size:12px;
        letter-spacing:0.3px;
        flex-shrink:0;
    }
    .confirm-name{
        color:#111827;
        font-weight:900;
        font-size:14px;
        line-height:1.35;
        max-width:520px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        margin:0;
        opacity:.85;
    }
    .confirm-cf{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 12px;
        border-radius:14px;
        border:1px solid #fde68a;
        background:#fffef5;
        color:#111827;
        font-weight:900;
        min-width:170px;
        justify-content:center;
        white-space:nowrap;
    }
    .confirm-cf.high{ background:#ecfdf5; border-color:#86efac; color:#065f46; }
    .confirm-cf.mid { background:#eff6ff; border-color:#93c5fd; color:#1e3a8a; }
    .confirm-cf.low { background:#fff7ed; border-color:#fdba74; color:#7c2d12; }
    .confirm-cf.zero{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }

    .confirm-actions{
        display:flex;
        gap:10px;
        justify-content:flex-end;
        padding:14px 20px 18px;
        border-top:1px solid #fde68a;
        background:#ffffff;
    }
    .confirm-btn{
        border:none;
        border-radius:14px;
        padding:12px 18px;
        font-weight:900;
        cursor:pointer;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:10px;
        transition:all .15s ease;
        min-width:120px;
    }
    .confirm-btn.ghost{ background:#ffffff; border:1px solid #fde68a; color:#111827; }
    .confirm-btn.ghost:hover{ border-color:#f59e0b; box-shadow:0 0 0 4px rgba(245,158,11,0.14); transform: translateY(-1px); }
    .confirm-btn.primary{ background:#f59e0b; color:#fff; box-shadow:0 10px 24px rgba(245,158,11,0.25); }
    .confirm-btn.primary:hover{ background:#d97706; transform: translateY(-1px); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('diagnosa-form');
    const selectedCountEl = document.getElementById('selected-count');

    const fpSelectedEl = document.getElementById('fp-selected');
    const fpFilledEl   = document.getElementById('fp-filled');
    const fpPercentEl  = document.getElementById('fp-percent');
    const fpBarFillEl  = document.getElementById('fp-bar-fill');

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

    // Modal konfirmasi
    const overlay = document.getElementById('confirm-overlay');
    const listEl  = document.getElementById('confirm-list');
    const subEl   = document.getElementById('confirm-sub');

    const btnClose = document.getElementById('confirm-close');
    const btnBack  = document.getElementById('confirm-back');
    const btnGo    = document.getElementById('confirm-go');

    function buildConfirmList() {
        const checkedRows = getCheckedRows();
        subEl.textContent = `${checkedRows.length} gejala dipilih`;
        listEl.innerHTML = '';

        checkedRows.forEach(row => {
            const code = row.querySelector('.gejala-code')?.innerText ?? '';
            const name = row.querySelector('.gejala-name')?.innerText ?? '';
            const cf   = row.querySelector('.cf-hidden')?.value ?? '';

            const item = document.createElement('div');
            item.className = 'confirm-item';

            const level =
                (cf === "1") ? "high" :
                (cf === "0.8" || cf === "0.6") ? "mid" :
                (cf === "0.4" || cf === "0.2") ? "low" : "zero";

            item.innerHTML = `
                <div class="confirm-main">
                    <div class="confirm-code">${code}</div>
                    <div class="confirm-name">${name}</div>
                </div>
                <div class="confirm-cf ${level}"><b>CF</b> ${cf}</div>
            `;

            item.addEventListener('click', () => {
                closeConfirm();
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                row.classList.add('active');
                row.style.outline = '3px solid #f59e0b';
                setTimeout(() => row.style.outline = '', 1600);
            });

            listEl.appendChild(item);
        });
    }

    function openConfirm(){ overlay.style.display = 'flex'; }
    function closeConfirm(){ overlay.style.display = 'none'; }

    btnClose.addEventListener('click', closeConfirm);
    btnBack.addEventListener('click', closeConfirm);
    overlay.addEventListener('click', e => {
        if (e.target === overlay) closeConfirm();
    });

    btnGo.addEventListener('click', () => {
        closeConfirm();
        form.submit();
    });

    // Submit intercept
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const checkedRows = getCheckedRows();
        if (checkedRows.length === 0) {
            alert('⚠️ Silakan pilih minimal 1 gejala!');
            return;
        }

        let ok = true;
        checkedRows.forEach(row => {
            const hidden = row.querySelector('.cf-hidden');
            if (!hidden || hidden.value === '') {
                ok = false;
                row.querySelectorAll('.cf-btn').forEach(b => b.classList.add('need'));
                setTimeout(() => {
                    row.querySelectorAll('.cf-btn').forEach(b => b.classList.remove('need'));
                }, 1500);
            }
        });

        if (!ok) {
            alert('⚠️ Silakan pilih bobot CF untuk semua gejala!');
            return;
        }

        buildConfirmList();
        openConfirm();
    });

    updateProgress();
});
</script>

@endsection
