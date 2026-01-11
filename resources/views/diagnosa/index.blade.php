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

                {{-- ✅ Sebaris: meta kiri, tombol panduan kanan --}}
                <div class="search-meta-row">
                    <div class="search-meta" id="gejala-search-meta">Menampilkan semua gejala</div>

                    <button type="button" class="info-btn info-btn--small" id="open-guide" title="Panduan Diagnosa">
                        <i class="fas fa-circle-info"></i>
                        <span class="info-btn-text">Panduan</span>
                    </button>
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

{{-- ✅ MODAL PANDUAN --}}
<div class="guide-modal" id="guide-modal" aria-hidden="true">
    <div class="guide-backdrop" id="guide-backdrop"></div>

    <div class="guide-panel" role="dialog" aria-modal="true" aria-labelledby="guide-title">
        <div class="guide-head">
            <div>
                <div class="guide-title" id="guide-title">
                    <i class="fas fa-circle-info"></i> Panduan Diagnosa
                </div>
                <div class="guide-sub">Ikuti panduan berikut untuk melakukan diagnosa.</div>
            </div>

            <button type="button" class="guide-x" id="close-guide" title="Tutup">✕</button>
        </div>

        {{-- ✅ semua isi taruh di guide-body biar rapi --}}
        <div class="guide-body">

            {{-- ✅ Panduan CF (frekuensi/tingkat keyakinan) --}}
            <div class="guide-section">
                <div class="guide-section-title">
                    <i class="fas fa-sliders"></i> Panduan Mengisi Frekuensi / Tingkat Keyakinan (Bobot CF)
                </div>

                <div class="guide-section-desc">
                    Pilih tingkat keyakinan sesuai seberapa sering/kuat gejala terlihat pada ayam.
                    Semakin sering dan semakin jelas gejalanya, pilih bobot yang lebih tinggi.
                </div>

                <div class="cf-guide-grid">
                    <div class="cf-guide-item">
                        <div class="cf-guide-pill">Sedikit Yakin (0.2)</div>
                        <div class="cf-guide-text">
                            Gejala <b>jarang</b> muncul / <b>ringan</b> / masih ragu.
                            <br><span class="cf-guide-ex">Contoh: muncul sesekali (± 1–2 kali).</span>
                        </div>
                    </div>

                    <div class="cf-guide-item">
                        <div class="cf-guide-pill">Cukup Yakin (0.4)</div>
                        <div class="cf-guide-text">
                            Gejala <b>kadang</b> muncul, mulai terlihat jelas tapi belum konsisten.
                            <br><span class="cf-guide-ex">Contoh: muncul beberapa kali (± 3–4 kali).</span>
                        </div>
                    </div>

                    <div class="cf-guide-item">
                        <div class="cf-guide-pill">Hampir Yakin (0.6)</div>
                        <div class="cf-guide-text">
                            Gejala <b>sering</b> muncul dan cukup kuat, kemungkinan besar benar.
                            <br><span class="cf-guide-ex">Contoh: sering terlihat (± 5–7 kali).</span>
                        </div>
                    </div>

                    <div class="cf-guide-item">
                        <div class="cf-guide-pill">Yakin (0.8)</div>
                        <div class="cf-guide-text">
                            Gejala <b>sangat sering</b> muncul, jelas dan konsisten.
                            <br><span class="cf-guide-ex">Contoh: hampir setiap waktu dicek.</span>
                        </div>
                    </div>

                    <div class="cf-guide-item">
                        <div class="cf-guide-pill">Sangat Yakin (1.0)</div>
                        <div class="cf-guide-text">
                            Gejala <b>selalu</b> muncul / sangat jelas / sudah pasti terlihat.
                            <br><span class="cf-guide-ex">Contoh: setiap observasi pasti ada.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ langkah diagnosa --}}
            <ol class="guide-steps">
                <li><b>Cari gejala</b> (opsional) lewat kolom pencarian.</li>
                <li><b>Centang gejala</b> yang dialami ayam.</li>
                <li>Untuk tiap gejala yang dicentang, pilih <b>bobot CF</b> (Sedikit–Sangat Yakin).</li>
                <li>Pastikan semua gejala yang dicentang sudah punya bobot (biar progress 100%).</li>
                <li>Klik <b>Proses Diagnosa</b> untuk melihat hasil.</li>
            </ol>

            <div class="guide-note">
                <b>Tips:</b> Kalau tombol bobot belum dipilih, sistem akan menandai barisnya dan muncul peringatan.
            </div>
        </div>

        <div class="guide-foot">
            <button type="button" class="btn btn-outline" id="close-guide-2">Tutup</button>
        </div>
    </div>
</div>

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

    // Submit intercept
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const checkedRows = getCheckedRows();

        if (checkedRows.length === 0) {
            showToast('Silakan pilih minimal 1 gejala.', 'warn');
            return;
        }

        const rowsWithValue = checkedRows.filter(row => {
            const v = row.querySelector('.cf-hidden')?.value;
            return (v !== undefined && v !== null && v !== '');
        });

        if (rowsWithValue.length === 0) {
            showToast('Pilih minimal 1 gejala lalu tentukan bobot CF-nya.', 'warn');
            checkedRows.forEach(row => {
                row.querySelectorAll('.cf-btn').forEach(b => b.classList.add('need'));
                setTimeout(() => row.querySelectorAll('.cf-btn').forEach(b => b.classList.remove('need')), 1400);
            });
            return;
        }

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

        form.submit();
    });

    updateProgress();

    /* =============================
       ===== Panduan Diagnosa =====
       ============================= */
    const openGuide = document.getElementById('open-guide');
    const guideModal = document.getElementById('guide-modal');
    const closeGuide = document.getElementById('close-guide');
    const closeGuide2 = document.getElementById('close-guide-2');
    const guideBackdrop = document.getElementById('guide-backdrop');

    function showGuide(){
        if (!guideModal) return;
        guideModal.classList.add('show');
        guideModal.setAttribute('aria-hidden', 'false');
    }
    function hideGuide(){
        if (!guideModal) return;
        guideModal.classList.remove('show');
        guideModal.setAttribute('aria-hidden', 'true');
    }

    openGuide?.addEventListener('click', showGuide);
    closeGuide?.addEventListener('click', hideGuide);
    closeGuide2?.addEventListener('click', hideGuide);
    guideBackdrop?.addEventListener('click', hideGuide);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') hideGuide();
    });
});
</script>

<style>
/* ====== Balikin pembatas antar gejala ====== */
.gejala-container{
  border: 1px solid #f3d08a;
  border-radius: 14px;
  overflow: hidden;
}
.gejala-row{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
  padding:12px 14px;
  border-bottom:1px solid #f3d08a;
}
.gejala-row:last-child{ border-bottom:none; }
.gejala-row:hover{ background:#fff7e6; }
.gejala-row.active{ background:#fff3cf; }

/* ✅ sebaris meta + tombol panduan */
.search-meta-row{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-top:8px;
}
.search-meta-row .search-meta{
  flex:1;
  min-width:0;
}

/* tombol panduan kanan */
.info-btn{
  border:1px solid #f3d08a;
  background:#fffdf7;
  color:#000;
  cursor:pointer;
  font-weight:800;
  transition:.15s ease;
}
.info-btn:hover{ background:#fff7e6; }
.info-btn--small{
  height:34px;
  padding:0 12px;
  border-radius:10px;
  display:inline-flex;
  align-items:center;
  gap:8px;
  white-space:nowrap;
}
.info-btn-text{
  font-weight:800;
  font-size:13px;
}

/* ===== Modal Panduan ===== */
.guide-modal{
  position:fixed;
  inset:0;
  display:none;
  z-index:9999;
}
.guide-modal.show{ display:block; }

.guide-backdrop{
  position:absolute;
  inset:0;
  background:rgba(0,0,0,.35);
}

.guide-panel{
  position:relative;
  max-width:720px;
  width:calc(100% - 32px);
  margin:80px auto;
  background:#fffdf7;
  border:1px solid #f3d08a;
  border-radius:16px;
  box-shadow:0 14px 40px rgba(0,0,0,.2);
  overflow:hidden;
}

.guide-head{
  display:flex;
  justify-content:space-between;
  gap:14px;
  padding:16px 16px 12px 16px;
  border-bottom:1px solid #f3d08a;
}
.guide-title{
  font-weight:900;
  font-size:18px;
  color:#000;
  display:flex;
  align-items:center;
  gap:10px;
}
.guide-sub{
  font-size:13px;
  color:#6b7280;
  margin-top:4px;
}
.guide-x{
  border:none;
  background:transparent;
  font-size:18px;
  cursor:pointer;
  width:38px;
  height:38px;
  border-radius:10px;
}
.guide-x:hover{ background:#fff7e6; }

.guide-body{
  padding:16px;
}

.guide-steps{
  margin:0;
  padding-left:18px;
  color:#000;
}
.guide-steps li{
  margin:8px 0;
  line-height:1.45;
}

.guide-note{
  margin-top:12px;
  padding:12px 14px;
  background:#fff7e6;
  border:1px solid #f3d08a;
  border-radius:12px;
  color:#2b1a00;
  font-size:14px;
}

.guide-foot{
  padding:14px 16px;
  border-top:1px solid #f3d08a;
  display:flex;
  justify-content:flex-end;
  gap:10px;
}

/* ===== Panduan CF (frekuensi) ===== */
.guide-section{
  margin-bottom:14px;
  padding-bottom:14px;
  border-bottom:1px dashed #f3d08a;
}
.guide-section-title{
  font-weight:900;
  color:#000;
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:6px;
}
.guide-section-desc{
  font-size:14px;
  color:#4b5563;
  margin-bottom:10px;
  line-height:1.45;
}
.cf-guide-grid{
  display:grid;
  grid-template-columns:1fr;
  gap:10px;
}
.cf-guide-item{
  border:1px solid #f3d08a;
  background:#fffdf7;
  border-radius:12px;
  padding:10px 12px;
}
.cf-guide-pill{
  display:inline-flex;
  align-items:center;
  padding:6px 10px;
  border-radius:999px;
  font-weight:900;
  font-size:13px;
  background:#fff7e6;
  border:1px solid #f3d08a;
  color:#000;
  margin-bottom:6px;
}
.cf-guide-text{
  font-size:14px;
  color:#2b1a00;
  line-height:1.45;
}
.cf-guide-ex{
  color:#6b7280;
  font-size:13px;
}
.guide-panel{
  max-height: calc(100vh - 140px);
  display:flex;
  flex-direction:column;
}
.guide-body{
  overflow:auto;
  flex:1; /* ✅ otomatis ngisi sisa tinggi */
}

</style>

@endsection
