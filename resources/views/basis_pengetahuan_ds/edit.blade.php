{{-- skeleton saja, strukturnya sama --}}
@foreach ($rules as $i => $rule)
    <div class="gejala-row">
        <select name="gejalas[{{ $i }}][gejala_id]" class="gejala-select" required>
            <option value="">-- Pilih Gejala --</option>
            @foreach($gejalas as $g)
                <option value="{{ $g->id }}"
                    {{ $g->id == $rule->gejala_id ? 'selected' : '' }}>
                    {{ $g->kode_gejala }} - {{ $g->nama_gejala }}
                </option>
            @endforeach
        </select>

        <select name="gejalas[{{ $i }}][ds_value]" class="cf-select" required>
            <option value="">-- Bobot DS --</option>
            @for ($v = 0.1; $v <= 1.0; $v += 0.1)
                @php $val = number_format($v, 1); @endphp
                <option value="{{ $val }}"
                    {{ $val == $rule->ds_value ? 'selected' : '' }}>
                    {{ $val }}
                </option>
            @endfor
        </select>

        <button type="button" class="btn-delete" onclick="hapusBaris(this)">✕</button>
    </div>
@endforeach
