@extends('layouts.app')

@section('title', 'Riwayat Diagnosa')

@section('content')
<div style="max-width:1800px;margin:0 auto;padding:24px;">
    <div style="margin-bottom:24px;">
        <h1 style="font-size:28px;font-weight:700;color:#111827;margin:0 0 8px 0;">Riwayat Diagnosa</h1>
        <p style="color:#6b7280;margin:0;font-size:15px;font-weight:500;">
            Daftar hasil diagnosa yang pernah dibuat (tanggal & jam + ringkasan).
        </p>
    </div>

    <div style="background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.05);padding:28px;">

        {{-- Filter Periode --}}
        <div style="margin-bottom:20px;">
            <form method="GET" action="{{ route('riwayat-diagnosa.index') }}"
                    style="display:flex;gap:14px;align-items:end;flex-wrap:wrap;margin:0;">
                <div>
                <label style="font-size:13px;color:#000;font-weight:600;">
                    Dari Tanggal
                </label>
                <input type="date" name="start_date"
                    value="{{ request('start_date') }}"
                    style="
                        padding:10px 14px;
                        border:1.5px solid #fbbf24;
                        border-radius:10px;
                        min-width:170px;
                        background:#fff;
                        color:#000;
                        font-weight:500;
                    ">
            </div>
                <div>
                <label style="font-size:13px;color:#000;font-weight:600;">
                    Sampai Tanggal
                </label>
                <input type="date"
                style="padding:10px 14px;
                border:1.5px solid #fbbf24;
                border-radius:10px;
                background:#fff;
                color:#000;
                font-weight:500;">

            </div>


                <button type="submit"
                        style="padding:11px 20px;border-radius:10px;background:#f59e0b;color:#fff;border:none;font-weight:700;cursor:pointer;box-shadow:0 2px 4px rgba(245,158,11,0.3);">
                    Tampilkan
                </button>

                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('riwayat-diagnosa.index') }}"
                       style="padding:11px 20px;border-radius:10px;background:#fff;color:#000;text-decoration:none;font-weight:700;border:2px solid #fbbf24;">
                        Reset
                    </a>
                @endif
            </form>

            @if(request('start_date') || request('end_date'))
                <div style="color:#000;margin-top:14px;font-size:14px;font-weight:500;">
                    📊 Menampilkan periode:
                    <b style="color:#000;font-weight:800;">{{ request('start_date') ?? '-' }}</b>
                    s/d
                    <b style="color:#000;font-weight:800;">{{ request('end_date') ?? '-' }}</b>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div style="background:#d1fae5;border:2px solid #10b981;padding:14px 18px;border-radius:12px;margin-bottom:20px;color:#065f46;font-weight:600;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($riwayats->count() === 0)
            <div style="margin-bottom:20px;">
                <div style="font-size:48px;margin-bottom:12px;">📋</div>
                <div style="font-size:13px;color:#000;font-weight:600;">Belum ada riwayat diagnosa.</div>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0;">
                    <thead>
                    <tr style="background:#fef3c7;">
                        <th style="
                            padding:16px;
                            text-align:center;
                            vertical-align:middle;
                            font-weight:700;
                            color:#000;
                            border-bottom:2px solid #fbbf24;
                            border-top-left-radius:12px;
                        ">
                            Tanggal & Jam
                        </th>

                        <th style="
                            padding:16px;
                            text-align:center;
                            vertical-align:middle;
                            font-weight:700;
                            color:#000;
                            border-bottom:2px solid #fbbf24;
                        ">
                            Judul
                        </th>

                        <th style="
                            padding:16px;
                            text-align:center;
                            vertical-align:middle;
                            font-weight:700;
                            color:#000;
                            border-bottom:2px solid #fbbf24;
                        ">
                            Ringkasan
                        </th>

                        <th style="
                            padding:16px;
                            text-align:center;
                            vertical-align:middle;
                            font-weight:700;
                            color:#000;
                            border-bottom:2px solid #fbbf24;
                            border-top-right-radius:12px;
                        ">
                            Aksi
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($riwayats as $r)
                        @php
                            $p = $r->payload ?? [];
                            $kesimpulan = $p['kesimpulan'] ?? null;

                            $topDS = $p['ranking']['ds'][0]['nama'] ?? $p['ranking']['ds'][0]['nama_penyakit'] ?? null;
                            $topCF = $p['ranking']['cf'][0]['nama'] ?? $p['ranking']['cf'][0]['nama_penyakit'] ?? null;

                            $ringkasan = $kesimpulan
                                ?: ($topDS ? "Hasil DS teratas: {$topDS}" : ($topCF ? "Hasil CF teratas: {$topCF}" : "Lihat detail diagnosa"));

                            $dt = $r->diagnosa_at ?? $r->created_at;
                        @endphp

                        <tr style="border-bottom:1px solid #fef3c7;transition:all 0.2s;"
                            onmouseover="this.style.background='#fffbeb'"
                            onmouseout="this.style.background='#fff'">

                            {{-- Tanggal & Jam --}}
                            <td style="padding:18px 16px;">
                                <div style="font-size:13px;color:#000;font-weight:600;">
                                    {{ optional($dt)->timezone('Asia/Jakarta')->format('d M Y') }}
                                </div>
                                <div style="color:#d97706;font-weight:500;font-size:13px;margin-top:4px;">
                                    {{ optional($dt)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </div>
                            </td>

                            {{-- Judul --}}
                            <td style="padding:18px 16px;">
                                <div style="font-size:14px;color:#000;font-weight:600;">
                                    {{ $r->judul ?? 'Hasil Diagnosa' }}
                                </div>
                            </td>

                            {{-- Ringkasan --}}
                            <td style="padding:18px 16px;color:#000;font-size:14px;font-weight:500;line-height:1.6;">
                                {{ $ringkasan }}
                            </td>

                            {{-- Aksi --}}
                            <td style="padding:18px 16px;white-space:nowrap;">
                                <a href="{{ route('riwayat-diagnosa.show', $r->id) }}"
                                style="padding:11px 20px;
                                        border-radius:10px;
                                        background:#f59e0b;
                                        color:#fff;
                                        border:none;
                                        font-weight:500;
                                        cursor:pointer;
                                        box-shadow:0 2px 4px rgba(245,158,11,0.3);
                                        text-decoration:none;"
                                onmouseover="this.style.textDecoration='none'"
                                onmouseout="this.style.textDecoration='none'">
                                    Detail
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:20px;">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
