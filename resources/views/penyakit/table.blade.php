<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3>Daftar Penyakit</h3>

        <a href="{{ route('penyakit.create') }}"
           style="
                background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
                border-radius: 999px;
                padding: 8px 18px;
                text-decoration:none;
                color:#5a4a2a;
                font-weight:600;
                box-shadow:0 4px 10px rgba(253,160,133,.5);
           ">
            + Tambah Penyakit
        </a>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
        <tr style="background:#fff7c2;">
            <th style="padding:10px 12px;width:40px;">#</th>
            <th style="padding:10px 12px;width:80px;">Kode</th>
            <th style="padding:10px 12px;width:180px;">Nama Penyakit</th>
            <th style="padding:10px 12px;">Deskripsi</th>
            <th style="padding:10px 12px;">Solusi</th>
            <th style="padding:10px 12px;width:130px;">Aksi</th>
        </tr>
        </thead>

        <tbody>
        @forelse($penyakits as $index => $penyakit)
            <tr style="background:#ffffff;border-top:1px solid #f1e4b5;">
                <td style="padding:8px 12px;">{{ $index + 1 }}</td>
                <td style="padding:8px 12px;">{{ $penyakit->kode_penyakit }}</td>
                <td style="padding:8px 12px;">{{ $penyakit->nama_penyakit }}</td>
                <td style="padding:8px 12px;">{{ $penyakit->deskripsi }}</td>
                <td style="padding:8px 12px;">{{ $penyakit->solusi }}</td>
                <td style="padding:8px 12px;white-space:nowrap;">
                    {{-- Tombol Edit --}}
                    <a href="{{ route('penyakit.edit', $penyakit->id) }}"
                       style="
                            display:inline-block;
                            padding:5px 14px;
                            border-radius:999px;
                            font-size:12px;
                            font-weight:600;
                            background:#fde68a;
                            color:#854d0e;
                            text-decoration:none;
                       ">
                        Edit
                    </a>

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('penyakit.destroy', $penyakit->id) }}"
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="
                                    padding:5px 14px;
                                    border-radius:999px;
                                    font-size:12px;
                                    font-weight:600;
                                    border:none;
                                    background:#fecaca;
                                    color:#b91c1c;
                                    cursor:pointer;
                                ">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding:14px;text-align:center;color:#9ca3af;">
                    Belum ada data penyakit.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
