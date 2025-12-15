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
            <b>Cara Menggunakan:</b> Centang gejala yang dialami ayam, lalu pilih bobot CF untuk tiap gejala.
            Minimal pilih <b>1 gejala</b> untuk melakukan diagnosa.
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
                    <i class="fas fa-mouse-pointer"></i>
                    Pilih gejala, lalu isi bobot CF
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
                            <span class="gejala-text">
                                <span class="gejala-code">{{ $g->kode_gejala }}</span>
                                <span class="gejala-name">{{ $g->nama_gejala }}</span>
                            </span>
                        </label>

                        <select name="cf_user[]"
                                class="cf-select"
                                disabled>
                            <option value="">-- Bobot CF --</option>
                            <option value="0">0 - Tidak</option>
                            <option value="0.2">0.2 - Sedikit Yakin</option>
                            <option value="0.4">0.4 - Cukup Yakin</option>
                            <option value="0.6">0.6 - Hampir Yakin</option>
                            <option value="0.8">0.8 - Yakin</option>
                            <option value="1">1 - Sangat Yakin</option>
                        </select>
                    </div>
                @endforeach
            </div>

            @error('gejala_id')
                <div class="error-message">
                    ⚠️ {{ $message }}
                </div>
            @enderror
        </div>

        <div class="btn-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-microscope"></i>
                Proses Diagnosa
            </button>
        </div>
    </form>
</div>

<style>
    /* ===== GLOBAL FONT ===== */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont,
                    'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        letter-spacing: -0.1px;
    }

    /* ===== PAGE HEADER ===== */
    .page-title {
        font-size: 30px;
        font-weight: 800;
        color: #1f2937; /* slate-800 */
        letter-spacing: -0.4px;
    }

    .page-subtitle {
        font-size: 15px;
        font-weight: 500;
        color: #6b7280; /* gray-500 */
        line-height: 1.7;
    }

    /* ===== CARD ===== */
    .form-card {
        font-size: 14px;
        color: #374151; /* gray-700 */
    }

    /* ===== FORM ===== */
    .form-label {
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .hint-pill {
        font-size: 12px;
        font-weight: 700;
    }

    /* ===== INFO BOX ===== */
    .info-box-text {
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
    }

    /* ===== STATS ===== */
    .stat-number {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    /* ===== GEJALA LIST ===== */
    .gejala-name {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .gejala-code {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* ===== SELECT ===== */
    .cf-select {
        font-size: 13px;
        font-weight: 600;
    }

    /* ===== BUTTON ===== */
    .btn-submit {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* ===== ERROR ===== */
    .error-message {
        font-size: 13px;
        font-weight: 600;
    }

    /* Selaras style (Basis CF/DS & Riwayat) */
    .form-card{
        background:#ffffff;
        border-radius:16px;
        padding:40px;
        box-shadow:0 4px 20px rgba(0,0,0,0.08);
        border:1px solid #fde68a;
        max-width:1800px;
        margin:0 auto;
    }

    .form-group{ margin:0; }

    .form-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        margin-bottom:16px;
        flex-wrap:wrap;
    }

    .form-label{
        margin:0;
        font-size:18px;
        font-weight:900;
        color:#78350f;
        display:flex;
        align-items:center;
        gap:10px;
    }
    .form-label i{ color:#f59e0b; }

    .required-mark{ color:#ef4444; margin-left:4px; font-weight:900; }

    .hint-pill{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 12px;
        border-radius:999px;
        background:#fffbeb;
        border:1px solid #fde68a;
        color:#92400e;
        font-weight:800;
        font-size:13px;
        white-space:nowrap;
    }
    .hint-pill i{ color:#f59e0b; }

    /* Info box */
    .info-box{
        background:#fffbeb;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:16px 18px;
        margin-bottom:18px;
        display:flex;
        align-items:flex-start;
        gap:12px;
    }
    .info-icon{
        width:36px;
        height:36px;
        border-radius:12px;
        background:#fff9c4;
        border:1px solid #fde68a;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
        flex-shrink:0;
    }
    .info-box-text{
        font-size:14px;
        color:#92400e;
        line-height:1.6;
        font-weight:600;
    }

    /* Stats */
    .stats-banner{
        display:flex;
        gap:14px;
        margin-bottom:18px;
        max-width:1800px;
        margin-left:auto;
        margin-right:auto;
    }
    .stat-card{
        flex:1;
        background:#ffffff;
        border:1px solid #fde68a;
        border-radius:14px;
        padding:14px 18px;
        box-shadow:0 4px 16px rgba(0,0,0,0.05);
        text-align:left;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
    }
    .stat-number{
        font-size:26px;
        font-weight:900;
        color:#78350f;
        line-height:1;
    }
    .stat-label{
        font-size:13px;
        color:#92400e;
        font-weight:800;
        opacity:.9;
        text-align:right;
    }

    /* Gejala list */
    .gejala-container{
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .gejala-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        padding:14px 16px;
        background:#fffef5;
        border:1px solid #fde68a;
        border-radius:14px;
        transition:all .18s ease;
    }

    .gejala-row:hover{
        background:#fff9c4;
        transform: translateY(-1px);
        box-shadow:0 6px 18px rgba(245,158,11,0.12);
    }

    .gejala-row.active{
        background:#fffbeb;
        border-color:#f59e0b;
        box-shadow:0 8px 22px rgba(245,158,11,0.18);
    }

    .checkbox-wrapper{
        display:flex;
        align-items:flex-start;
        gap:12px;
        flex:1;
        margin:0;
        cursor:pointer;
        user-select:none;
    }

    .cb-gejala{
        width:20px;
        height:20px;
        cursor:pointer;
        accent-color:#f59e0b;
        flex-shrink:0;
        margin-top:2px;
    }

    .gejala-text{
        display:flex;
        align-items:baseline;
        flex-wrap:wrap;
        gap:10px;
        line-height:1.5;
    }

    .gejala-code{
        display:inline-flex;
        align-items:center;
        padding:4px 10px;
        border-radius:999px;
        background:#ffffff;
        border:1px solid #fde68a;
        color:#78350f;
        font-weight:900;
        font-size:12px;
        white-space:nowrap;
    }

    .gejala-name{
        font-size:14px;
        color:#78350f;
        font-weight:700;
    }

    /* Select */
    .cf-select{
        padding:10px 12px;
        border-radius:12px;
        border:1.8px solid #fde68a;
        font-size:14px;
        min-width:220px;
        color:#78350f;
        background:#ffffff;
        outline:none;
        transition:all .15s ease;
        font-weight:800;
    }

    .cf-select:disabled{
        background:#f9fafb;
        color:#9ca3af;
        cursor:not-allowed;
        opacity:.85;
        border-color:#fde68a;
    }

    .cf-select:not(:disabled):focus{
        border-color:#f59e0b;
        box-shadow:0 0 0 4px rgba(245,158,11,0.18);
    }

    /* Error */
    .error-message{
        display:flex;
        align-items:center;
        gap:10px;
        color:#7f1d1d;
        background:#fef2f2;
        padding:12px 14px;
        border-radius:12px;
        margin-top:14px;
        font-size:14px;
        border:1px solid #fecaca;
        font-weight:800;
    }

    /* Actions */
    .btn-actions{
        display:flex;
        gap:12px;
        margin-top:22px;
        padding-top:20px;
        border-top:1px solid #fde68a;
        justify-content:flex-end;
    }

    .btn{
        padding:12px 18px;
        border:none;
        border-radius:12px;
        font-size:14px;
        font-weight:900;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:10px;
        transition:all .2s ease;
        white-space:nowrap;
    }

    .btn-submit{
        background:#f59e0b;
        color:#ffffff;
        box-shadow:0 4px 12px rgba(245,158,11,0.25);
    }

    .btn-submit:hover{
        background:#d97706;
        transform: translateY(-1px);
        box-shadow:0 6px 16px rgba(245,158,11,0.35);
    }

    @media (max-width: 768px){
        .form-card{ padding:24px; }
        .stats-banner{ flex-direction:column; }
        .gejala-row{ flex-direction:column; align-items:stretch; }
        .cf-select{ min-width:100%; }
        .btn-actions{ justify-content:stretch; }
        .btn{ width:100%; justify-content:center; }
        .stat-label{ text-align:left; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('diagnosa-form');
    const selectedCountEl = document.getElementById('selected-count');

    // Update selected count
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.cb-gejala:checked').length;
        selectedCountEl.textContent = checkedCount;
        selectedCountEl.style.color = checkedCount > 0 ? '#16a34a' : '#78350f';
    }

    // Handle checkbox change
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('cb-gejala')) {
            const row = e.target.closest('.gejala-row');
            const select = row.querySelector('.cf-select');

            if (e.target.checked) {
                select.disabled = false;
                row.classList.add('active');
            } else {
                select.disabled = true;
                select.value = '';
                row.classList.remove('active');
            }

            updateSelectedCount();
        }
    });

    // Form validation
    form.addEventListener('submit', function (e) {
        const checkedBoxes = document.querySelectorAll('.cb-gejala:checked');

        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('⚠️ Silakan pilih minimal 1 gejala untuk melakukan diagnosa!');
            return;
        }

        // Validate CF selection for each checked gejala
        let allValid = true;
        checkedBoxes.forEach(checkbox => {
            const row = checkbox.closest('.gejala-row');
            const select = row.querySelector('.cf-select');

            if (!select.value) {
                allValid = false;
                select.style.borderColor = '#ef4444';
                setTimeout(() => {
                    select.style.borderColor = '#fde68a';
                }, 2000);
            }
        });

        if (!allValid) {
            e.preventDefault();
            alert('⚠️ Silakan pilih bobot CF untuk semua gejala yang dipilih!');
        }
    });

    // Initial count
    updateSelectedCount();
});
</script>

@endsection
