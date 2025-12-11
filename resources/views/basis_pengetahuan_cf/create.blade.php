@extends('layouts.app')

@section('title', 'Tambah Basis CF')

@section('content')

<style>
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    .form-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .form-group {
        margin-bottom: 28px;
    }

    .form-label {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: #34495e;
        margin-bottom: 10px;
    }

    .form-select {
        width: 100%;
        padding: 15px 18px;
        border: 2px solid #e8ecef;
        border-radius: 12px;
        font-size: 15px;
        color: #2c3e50;
        background: #ffffff;
        transition: all 0.3s ease;
        outline: none;
    }

    .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }

    .gejala-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
    }

    .section-label {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-label::before {
        content: "📋";
        font-size: 20px;
    }

    #gejala-wrapper {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .gejala-row {
        display: flex;
        gap: 12px;
        align-items: center;
        background: #ffffff;
        padding: 15px;
        border-radius: 12px;
        border: 2px solid #e8ecef;
        transition: all 0.3s ease;
    }

    .gejala-row:hover {
        border-color: #3498db;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
    }

    .gejala-select {
        flex: 2;
        padding: 12px 15px;
        border: 1px solid #dfe4ea;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
    }

    .gejala-select:focus {
        border-color: #3498db;
    }

    .cf-select {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #dfe4ea;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
    }

    .cf-select:focus {
        border-color: #3498db;
    }

    .btn-delete {
        background: #fee;
        border: 2px solid #fcc;
        border-radius: 10px;
        padding: 10px 15px;
        color: #e74c3c;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 45px;
    }

    .btn-delete:hover {
        background: #fcc;
        border-color: #e74c3c;
        transform: scale(1.05);
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        padding: 12px 24px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        color: #1976d2;
        border: 2px solid #90caf9;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #bbdefb, #90caf9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
    }

    .btn-actions {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit {
        padding: 15px 40px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #f6b93b, #e58e26);
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(229, 142, 38, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(229, 142, 38, 0.4);
    }

    .btn-back {
        padding: 15px 40px;
        border-radius: 12px;
        background: #ecf0f1;
        color: #2c3e50;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        border: 2px solid #dfe4ea;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-back:hover {
        background: #dfe4ea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .required-mark {
        color: #e74c3c;
        margin-left: 4px;
    }
</style>

<h1 class="page-title">Tambah Basis Pengetahuan CF</h1>

<div class="form-card">
    <form action="{{ route('basis_pengetahuan_cf.store') }}" method="POST">
    </form>
        @csrf

        {{-- Penyakit --}}
        <div class="form-group">
            <label class="form-label">
                Nama Penyakit
                <span class="required-mark">*</span>
            </label>
            <select name="penyakit_id" class="form-select" required>
                <option value="">-- Pilih Penyakit --</option>
                @foreach($penyakits as $p)
                    <option value="{{ $p->id }}">{{ $p->kode_penyakit }} - {{ $p->nama_penyakit }}</option>
                @endforeach
            </select>
        </div>

        {{-- Gejala Section --}}
        <div class="gejala-section">
            <div class="section-label">
                Gejala & Bobot Certainty Factor
            </div>

            <div id="gejala-wrapper">
                {{-- Baris pertama --}}
                <div class="gejala-row">
                    <select name="gejalas[0][gejala_id]" class="gejala-select" required>
                        <option value="">-- Pilih Gejala --</option>
                        @foreach($gejalas as $g)
                            <option value="{{ $g->id }}">
                                {{ $g->kode_gejala }} - {{ $g->nama_gejala }}
                            </option>
                        @endforeach
                    </select>

                    <select name="gejalas[0][cf_value]" class="cf-select" required>
                        <option value="">-- Bobot CF --</option>
                        <option value="0">0 - Tidak</option>
                        <option value="0.2">0.2 - Sedikit Yakin</option>
                        <option value="0.4">0.4 - Cukup Yakin</option>
                        <option value="0.6">0.6 - Hampir Yakin</option>
                        <option value="0.8">0.8 - Yakin</option>
                        <option value="1">1 - Sangat Yakin</option>
                    </select>

                    <button type="button" class="btn-delete" onclick="hapusBaris(this)">✕</button>
                </div>
            </div>

            {{-- Tombol tambah baris --}}
            <button type="button" class="btn-add" onclick="tambahGejala()">
                <span style="font-size: 18px;">+</span>
                Tambah Gejala
            </button>
        </div>

        {{-- Action Buttons --}}
        <div class="btn-actions">
            <button type="submit" class="btn-submit">
                Simpan
            </button>
            <a href="{{ route('basis_pengetahuan_cf.index') }}" class="btn-back">
                Kembali
            </a>
        </div>
    </form>
</div>
<script>
// Fungsi global supaya bisa dipanggil dari mana saja
function updateDisabledOptions() {
    // Ambil semua select gejala
    const selects = document.querySelectorAll('.gejala-select');

    // Ambil semua value yang sudah dipilih
    const selectedValues = Array.from(selects)
        .map(s => s.value)
        .filter(v => v !== "");

    // Loop tiap select
    selects.forEach(select => {
        const currentValue = select.value; // value select ini sendiri

        Array.from(select.options).forEach(option => {
            if (option.value === "") return; // skip placeholder

            // Kalau option sudah dipakai di select lain → disable
            if (selectedValues.includes(option.value) && option.value !== currentValue) {
                option.disabled = true;
                option.style.color = "#999"; // abu-abu
            } else {
                option.disabled = false;
                option.style.color = "#000";
            }
        });
    });
}

// Jalan saat halaman baru selesai load
document.addEventListener('DOMContentLoaded', function () {
    updateDisabledOptions();
});

// Jalan tiap kali ada perubahan di select gejala
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('gejala-select')) {
        updateDisabledOptions();
    }
});
</script>

<script>
let index = 1;

function tambahGejala() {
    let wrapper = document.getElementById('gejala-wrapper');

    let html = `
    <div class="gejala-row">
        <select name="gejalas[${index}][gejala_id]" class="gejala-select" required>
            <option value="">-- Pilih Gejala --</option>
            @foreach($gejalas as $g)
                <option value="{{ $g->id }}">{{ $g->kode_gejala }} - {{ $g->nama_gejala }}</option>
            @endforeach
        </select>

        <select name="gejalas[${index}][cf_value]" class="cf-select" required>
            <option value="">-- Bobot CF --</option>
            <option value="0">0 - Tidak</option>
            <option value="0.2">0.2 - Sedikit Yakin</option>
            <option value="0.4">0.4 - Cukup Yakin</option>
            <option value="0.6">0.6 - Hampir Yakin</option>
            <option value="0.8">0.8 - Yakin</option>
            <option value="1">1 - Sangat Yakin</option>
        </select>

        <button type="button" class="btn-delete" onclick="hapusBaris(this)">✕</button>
    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    index++;

    // setelah nambah row → update disabled option
    updateDisabledOptions();
}

function hapusBaris(btn) {
    let wrapper = document.getElementById('gejala-wrapper');
    let rows = wrapper.querySelectorAll('.gejala-row');

    if (rows.length > 1) {
        btn.closest('.gejala-row').remove();
        // setelah hapus row → update lagi
        updateDisabledOptions();
    } else {
        alert('Minimal harus ada 1 gejala!');
    }
}
</script>

@endsection
