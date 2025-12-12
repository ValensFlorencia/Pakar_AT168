@extends('layouts.app')

@section('title', 'Detail Basis CF')

@section('content')

<h1 class="page-title">Detail Basis Pengetahuan CF</h1>
<p class="page-subtitle">
    Penyakit: <b>{{ $penyakit->kode_penyakit }} - {{ $penyakit->nama_penyakit }}</b>
</p>

<div style="background:#ffffff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);padding:25px 30px 30px;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
        <h2 style="font-size:18px;font-weight:700;margin:0;">Daftar Gejala & Bobot CF</h2>

        <a href="{{ route('basis_pengetahuan_cf.index') }}"
           style="padding:10px 22px;border-radius:999px;background:#ecf0f1;color:#2c3e50;font-weight:600;text-decoration:none;border:2px solid #dfe4ea;">
            Kembali
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#fff4bf;">
                    <th>#</th>
                    <th>Kode Gejala</th>
                    <th>Nama Gejala</th>
                    <th>Bobot CF</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rules as $i => $row)
                <tr style="background: {{ $i%2==0 ? '#fffef5' : '#fffbe2' }};">
                    <td style="text-align:center">{{ $i+1 }}</td>
                    <td style="text-align:center">{{ $row->gejala->kode_gejala }}</td>
                    <td>{{ $row->gejala->nama_gejala }}</td>
                    <td style="text-align:center">{{ $row->cf_value }}</td>
                    <td style="text-align:center">
                        <div style="display:flex; gap:8px; justify-content:center;">
                            <a href="{{ route('basis_pengetahuan_cf.edit', $row->id) }}"
                               style="padding:6px 14px;border-radius:999px;background:#f6b93b;color:#fff;text-decoration:none;">
                                Edit
                            </a>

                            <form action="{{ route('basis_pengetahuan_cf.destroy', $row->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="padding:6px 14px;border-radius:999px;background:#ffcccc;color:#b91c1c;border:none;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:20px;">
                        Belum ada gejala untuk penyakit ini.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
