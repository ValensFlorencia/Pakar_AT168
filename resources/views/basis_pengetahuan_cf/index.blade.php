@extends('layouts.app')

@section('title', 'Basis Pengetahuan Certainty Factor')

@section('content')

<h1 class="page-title">Basis Pengetahuan Certainty Factor</h1>
<p class="page-subtitle">Relasi antara penyakit, gejala, dan bobot certainty factor.</p>

<div style="background:#ffffff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);padding:25px 30px 30px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:18px; font-weight:700; margin:0;">Daftar Penyakit (Basis CF)</h2>

        <a href="{{ route('basis_pengetahuan_cf.create') }}"
           style="padding:10px 22px;border-radius:999px;background:linear-gradient(135deg,#f6b93b,#e58e26);color:#fff;font-weight:600;text-decoration:none;">
            + Tambah Basis CF
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#fff4bf;">
                    <th>#</th>
                    <th>Kode Penyakit</th>
                    <th>Nama Penyakit</th>
                    <th>Jumlah Gejala</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($penyakits as $i => $p)
                <tr style="background: {{ $i%2==0 ? '#fffef5' : '#fffbe2' }};">
                    <td style="text-align:center">{{ $i+1 }}</td>
                    <td style="text-align:center">{{ $p->kode_penyakit }}</td>
                    <td>{{ $p->nama_penyakit }}</td>
                    <td style="text-align:center">{{ $p->basis_c_f_count }}</td>
                    <td style="text-align:center">
                        <a href="{{ route('basis_pengetahuan_cf.detail_penyakit', $p->id) }}"
                           style="padding:6px 14px;border-radius:999px;background:#3498db;color:#fff;text-decoration:none;">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:20px;">
                        Belum ada basis pengetahuan CF
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
