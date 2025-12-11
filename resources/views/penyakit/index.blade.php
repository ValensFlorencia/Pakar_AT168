@extends('layouts.app')

@section('title', 'Data Penyakit')

@section('content')
    <h1 class="page-title">Data Penyakit Ayam Petelur</h1>
    <p class="page-subtitle">
        Kelola daftar penyakit yang digunakan pada proses diagnosa sistem pakar.
    </p>

    @if (session('success'))
        <div style="
            background:#ecfdf3;
            border-left:4px solid #16a34a;
            padding:10px 15px;
            border-radius:10px;
            color:#166534;
            font-size:14px;
            margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>Daftar Penyakit</h3>
            <a href="{{ route('penyakit.create') }}" class="btn-primary">+ Tambah Penyakit</a>
        </div>

        <table>
            <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th style="width:80px;">Kode</th>
                <th style="width:180px;">Nama Penyakit</th>
                <th>Deskripsi</th>
                <th>Solusi</th>
                <th style="width:130px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($penyakits as $index => $penyakit)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penyakit->kode_penyakit }}</td>
                    <td>{{ $penyakit->nama_penyakit }}</td>
                    <td>{{ $penyakit->deskripsi }}</td>
                    <td>{{ $penyakit->solusi }}</td>
                    <td class="actions">
                        <a href="{{ route('penyakit.edit', $penyakit->id) }}">
                            <button type="button" class="btn-edit">Edit</button>
                        </a>
                        <form action="{{ route('penyakit.destroy', $penyakit->id) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('Yakin ingin menghapus penyakit ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:18px; color:#999;">
                        Belum ada data penyakit.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
