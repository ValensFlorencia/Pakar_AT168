@extends('layouts.app')

@section('title', 'Diagnosa Penyakit')

@section('content')

<style>
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .form-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-label {
        display: block;
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label::before {
        content: "🔍";
        font-size: 22px;
    }

    .required-mark {
        color: #e74c3c;
        margin-left: 4px;
    }

    .gejala-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .gejala-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        background: #f8f9fa;
        border: 2px solid #e8ecef;
        border-radius: 14px;
        transition: all 0.3s ease;
    }

    .gejala-row:hover {
        background: #ffffff;
        border-color: #3498db;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
        transform: translateX(4px);
    }

    .gejala-row.active {
        background: #e8f4fd;
        border-color: #3498db;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        margin: 0;
        cursor: pointer;
    }

    .cb-gejala {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #3498db;
        flex-shrink: 0;
    }

    .gejala-text {
        font-size: 15px;
        color: #2c3e50;
        line-height: 1.5;
    }

    .gejala-code {
        font-weight: 700;
        color: #3498db;
    }

    .cf-select {
        padding: 10px 15px;
        border-radius: 10px;
        border: 2px solid #dfe4ea;
        font-size: 14px;
        min-width: 190px;
        color: #2c3e50;
        background: #ffffff;
        outline: none;
        transition: all 0.3s ease;
    }

    .cf-select:disabled {
        background: #ecf0f1;
        color: #95a5a6;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .cf-select:not(:disabled) {
        border-color: #3498db;
        cursor: pointer;
    }

    .cf-select:not(:disabled):focus {
        border-color: #2980b9;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #e74c3c;
        background: #fee;
        padding: 12px 16px;
        border-radius: 10px;
        margin-top: 15px;
        font-size: 14px;
        border-left: 4px solid #e74c3c;
    }

    .error-message::before {
        content: "⚠️";
        font-size: 18px;
    }

    .info-box {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border: 2px solid #90caf9;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 25px;
        display: flex;
        align-items: start;
        gap: 12px;
    }

    .info-box::before {
        content: "💡";
        font-size: 24px;
        flex-shrink: 0;
    }

    .info-box-text {
        font-size: 14px;
        color: #1976d2;
        line-height: 1.6;
    }

    .btn-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit {
        padding: 16px 45px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-submit::before {
        content: "🔬";
        font-size: 20px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9, #1f618d);
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .stats-banner {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        flex: 1;
        background: linear-gradient(135deg, #fff5e6, #ffe6cc);
        border: 2px solid #ffcc99;
        border-radius: 12px;
        padding: 15px 20px;
        text-align: center;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #e67e22;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: #d35400;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .gejala-row {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .cf-select {
            min-width: 100%;
        }

        .stats-banner {
            flex-direction: column;
        }
    }
</style>

<h1 class="page-title">🐔 Diagnosa Penyakit Ayam</h1>
<p class="page-subtitle">
    Pilih gejala yang dialami ayam, lalu tentukan tingkat keyakinan (bobot CF) untuk setiap gejala.
</p>

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
        <div class="info-box-text">
            <strong>Cara Menggunakan:</strong> Centang gejala yang dialami ayam, lalu pilih tingkat keyakinan Anda untuk setiap gejala. Minimal pilih 1 gejala untuk melakukan diagnosa.
        </div>
    </div>

    <form action="{{ route('diagnosa.hasil') }}" method="POST" id="diagnosa-form">
        @csrf

        <div class="form-group">
            <label class="form-label">
                Pilih Gejala & Bobot CF
                <span class="required-mark">*</span>
            </label>

            <div class="gejala-container">
                @foreach($gejalas as $g)
                    <div class="gejala-row" data-row="{{ $g->id }}">
                        <label class="checkbox-wrapper">
                            <input type="checkbox"
                                   name="gejala_id[]"
                                   value="{{ $g->id }}"
                                   class="cb-gejala">
                            <span class="gejala-text">
                                <span class="gejala-code">{{ $g->kode_gejala }}</span> - {{ $g->nama_gejala }}
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
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="btn-actions">
            <button type="submit" class="btn-submit">
                Proses Diagnosa
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('diagnosa-form');
    const selectedCountEl = document.getElementById('selected-count');

    // Update selected count
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.cb-gejala:checked').length;
        selectedCountEl.textContent = checkedCount;
        selectedCountEl.style.color = checkedCount > 0 ? '#27ae60' : '#e67e22';
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
                select.style.borderColor = '#e74c3c';
                setTimeout(() => {
                    select.style.borderColor = '#3498db';
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
