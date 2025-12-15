<div style="
    background:#ffffff;
    border-radius:16px;
    padding:40px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    border:1px solid #fde68a;
">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        margin-bottom:18px;
        padding-bottom:18px;
        border-bottom:1px solid #fde68a;
    ">
        <div>
            <h3 style="
                margin:0;
                font-size:18px;
                font-weight:800;
                color:#5a4a2a;
                display:flex;
                align-items:center;
                gap:10px;
            ">
                <i class="fas fa-virus" style="color:#f59e0b;"></i>
                Daftar Penyakit
            </h3>

            <div style="
                margin-top:8px;
                display:flex;
                align-items:center;
                gap:8px;
                font-size:13px;
                color:#92400e;
                opacity:.85;
            ">
                <i class="fas fa-info-circle" style="font-size:12px;"></i>
                Data penyakit yang digunakan dalam proses diagnosa.
            </div>
        </div>

        <a href="{{ route('penyakit.create') }}"
           style="
                padding:13px 28px;
                border-radius:10px;
                background:#f59e0b; /* SOLID */
                color:#ffffff;
                text-decoration:none;
                font-weight:600;
                display:inline-flex;
                align-items:center;
                gap:8px;
                box-shadow:0 4px 12px rgba(245,158,11,0.3);
                transition:all .2s ease;
                white-space:nowrap;
           "
           onmouseover="this.style.background='#d97706'; this.style.transform='translateY(-2px)';"
           onmouseout="this.style.background='#f59e0b'; this.style.transform='translateY(0)';"
        >
            <i class="fas fa-plus"></i>
            Tambah Penyakit
        </a>
    </div>

    <div style="
        overflow-x:auto;
        border-radius:12px;
        border:1px solid #fde68a;
        background:#ffffff;
    ">
        <table style="width:100%;border-collapse:collapse;font-size:14px;background:white;">
            <thead style="background:#fff9c4;">
            <tr>
                <th style="padding:12px 12px;width:60px;text-align:center;border-bottom:1px solid #fde68a;">#</th>
                <th style="padding:12px 12px;width:100px;border-bottom:1px solid #fde68a;">Kode</th>
                <th style="padding:12px 12px;width:220px;border-bottom:1px solid #fde68a;">Nama Penyakit</th>
                <th style="padding:12px 12px;border-bottom:1px solid #fde68a;">Deskripsi</th>
                <th style="padding:12px 12px;border-bottom:1px solid #fde68a;">Solusi</th>
                <th style="padding:12px 12px;width:220px;text-align:center;border-bottom:1px solid #fde68a;">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($penyakits as $index => $penyakit)
                <tr style="background: {{ $index % 2 === 0 ? '#fffef5' : '#fffbe2' }};">
                    <td style="padding:12px 12px;text-align:center;border-bottom:1px solid #fde68a;">
                        {{ $index + 1 }}
                    </td>
                    <td style="padding:12px 12px;font-weight:700;border-bottom:1px solid #fde68a;">
                        {{ $penyakit->kode_penyakit }}
                    </td>
                    <td style="padding:12px 12px;border-bottom:1px solid #fde68a;">
                        {{ $penyakit->nama_penyakit }}
                    </td>
                    <td style="padding:12px 12px;border-bottom:1px solid #fde68a;">
                        {{ $penyakit->deskripsi }}
                    </td>
                    <td style="padding:12px 12px;border-bottom:1px solid #fde68a;">
                        {{ $penyakit->solusi }}
                    </td>
                    <td style="padding:12px 12px;text-align:center;white-space:nowrap;border-bottom:1px solid #fde68a;">
                        <div style="display:flex;gap:10px;justify-content:center;align-items:center;flex-wrap:wrap;">

                            {{-- Tombol Edit --}}
                            <a href="{{ route('penyakit.edit', $penyakit->id) }}"
                               style="
                                    display:inline-flex;
                                    align-items:center;
                                    gap:8px;
                                    padding:8px 14px;
                                    border-radius:999px;
                                    font-size:13px;
                                    font-weight:700;
                                    background:#f59e0b;
                                    color:#ffffff;
                                    text-decoration:none;
                                    box-shadow:0 4px 12px rgba(245,158,11,.22);
                                    transition:all .2s ease;
                               "
                               onmouseover="this.style.background='#d97706'; this.style.transform='translateY(-1px)';"
                               onmouseout="this.style.background='#f59e0b'; this.style.transform='translateY(0)';"
                            >
                                <i class="fas fa-pen" style="font-size:12px;"></i> Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('penyakit.destroy', $penyakit->id) }}"
                                  method="POST"
                                  style="display:inline;margin:0;"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="
                                            display:inline-flex;
                                            align-items:center;
                                            gap:8px;
                                            padding:8px 14px;
                                            border-radius:999px;
                                            font-size:13px;
                                            font-weight:700;
                                            border:1px solid #fecaca;
                                            background:#fef2f2;
                                            color:#b91c1c;
                                            cursor:pointer;
                                            transition:all .2s ease;
                                        "
                                        onmouseover="this.style.background='#fee2e2'; this.style.transform='translateY(-1px)';"
                                        onmouseout="this.style.background='#fef2f2'; this.style.transform='translateY(0)';"
                                >
                                    <i class="fas fa-trash" style="font-size:12px;"></i> Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:22px;text-align:center;color:#8b7355;font-weight:600;background:#fffbeb;">
                        Belum ada data penyakit.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
