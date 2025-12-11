@extends('layouts.app')

@section('title', 'Basis Pengetahuan Dempster-Shafer')

@section('content')

<h1 class="page-title">Basis Pengetahuan Dempster-Shafer</h1>
<p class="page-subtitle">
    Relasi antara penyakit, gejala, dan bobot DS (Dempster-Shafer).
</p>

<div style="
    background:#ffffff;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    padding:25px 30px 30px;
">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:18px; font-weight:700; margin:0;">Daftar Basis DS</h2>

        <a href="{{ route('basis_pengetahuan_ds.create') }}"
           style="
                padding:10px 22px;
                border-radius:999px;
                background:linear-gradient(135deg,#f6b93b,#e58e26);
                color:#fff;
                font-weight:600;
                text-decoration:none;
           ">
            + Tambah Basis DS
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#fff4bf;">
                    <th>#</th>
                    <th>Kode Penyakit</th>
                    <th>Nama Penyakit</th>
                    <th>Kode Gejala</th>
                    <th>Nama Gejala</th>
                    <th>Bobot DS</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($basisDS as $i => $row)
                <tr style="background: {{ $i % 2 == 0 ? '#fffef5' : '#fffbe2' }};">
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->penyakit->kode_penyakit }}</td>
                    <td>{{ $row->penyakit->nama_penyakit }}</td>
                    <td>{{ $row->gejala->kode_gejala }}</td>
                    <td>{{ $row->gejala->nama_gejala }}</td>
                    <td style="text-align:center">{{ $row->ds_value }}</td>

                    <td>
                        <div style="display:flex; gap:8px; justify-content:center;">

                            <a href="{{ route('basis_pengetahuan_ds.edit', $row->id) }}"
                               style="padding:6px 14px;border-radius:999px;background:#f6b93b;color:#fff;text-decoration:none;">
                               Edit
                            </a>

                            <form action="{{ route('basis_pengetahuan_ds.destroy', $row->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf @method('DELETE')
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
                    <td colspan="7" style="text-align:center;padding:20px;">
                        Belum ada basis pengetahuan DS
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
