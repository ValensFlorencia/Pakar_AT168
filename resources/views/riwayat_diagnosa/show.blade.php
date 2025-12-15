@extends('layouts.app')

@section('title', 'Detail Riwayat Diagnosa')

@section('content')
<div style="max-width:1800px;margin:0 auto;padding:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#111827;margin-bottom:24px;">Detail Riwayat Diagnosa</h1>

    @php
        $p = $riwayat->payload ?? [];
        $gejala = $p['gejala_terpilih'] ?? [];
        $ranking = $p['ranking'] ?? [];
        $kesimpulan = $p['kesimpulan'] ?? null;
    @endphp

    <div style="background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.05);padding:32px;margin-bottom:24px;">

        {{-- Header Section --}}
        <div style="display:flex;justify-content:space-between;align-items:start;gap:20px;flex-wrap:wrap;padding-bottom:24px;border-bottom:2px solid #f3f4f6;">
            <div>
                <div style="font-size:24px;font-weight:800;color:#111827;margin-bottom:8px;">
                    {{ $riwayat->judul ?? 'Hasil Diagnosa' }}
                </div>
                <div style="display:flex;align-items:center;gap:8px;color:#6b7280;font-size:14px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span><b>{{ ($riwayat->diagnosa_at ?? $riwayat->created_at)->format('d M Y H:i') }}</b> WIB</span>
                </div>
            </div>

            <a href="{{ route('riwayat-diagnosa.index') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:10px;background:#111827;color:#fff;text-decoration:none;font-weight:600;font-size:14px;transition:all 0.2s;">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                   <line x1="19" y1="12" x2="5" y2="12"></line>
                   <polyline points="12 19 5 12 12 5"></polyline>
               </svg>
               Kembali
            </a>
        </div>

        {{-- Kesimpulan --}}
        <div style="margin-top:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:4px;height:24px;background:#10b981;border-radius:2px;"></div>
                <h2 style="font-size:18px;font-weight:800;color:#111827;margin:0;">Kesimpulan</h2>
            </div>
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:20px;">
                <p style="color:#166534;line-height:1.7;margin:0;font-size:15px;">
                    {{ $kesimpulan ?? 'Tidak ada kesimpulan tersimpan.' }}
                </p>
            </div>
        </div>

        {{-- Gejala Terpilih --}}
        <div style="margin-top:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:4px;height:24px;background:#3b82f6;border-radius:2px;"></div>
                <h2 style="font-size:18px;font-weight:800;color:#111827;margin:0;">Gejala Terpilih</h2>
            </div>
            <div style="background:#eff6ff;border:1px solid #93c5fd;border-radius:12px;padding:20px;">
                @if(empty($gejala))
                    <div style="color:#1e40af;">Tidak ada data gejala tersimpan.</div>
                @else
                    <ul style="margin:0;padding-left:20px;color:#1e3a8a;">
                        @foreach($gejala as $g)
                            <li style="margin-bottom:8px;line-height:1.6;">
                                <span style="font-weight:600;">
                                @if(is_array($g))
                                    {{ $g['kode_gejala'] ?? ($g['kode'] ?? '') }}
                                </span>
                                - {{ $g['nama_gejala'] ?? ($g['nama'] ?? ($g['gejala'] ?? '-')) }}

                                @if(isset($g['cf_user']))
                                    <span style="color:#60a5fa;font-size:14px;">
                                        (CF user: {{ $g['cf_user'] }})
                                    </span>
                                @endif
                                @else
                                    {{ $g }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Ranking Penyakit --}}
        <div style="margin-top:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="width:4px;height:24px;background:#f59e0b;border-radius:2px;"></div>
                <h2 style="font-size:18px;font-weight:800;color:#111827;margin:0;">Hasil Diagnosa (Ranking)</h2>
            </div>

            @php
                $isGrouped = is_array($ranking) && (array_key_exists('cf', $ranking) || array_key_exists('ds', $ranking));
                $groups = $isGrouped
                    ? [
                        'CF' => $ranking['cf'] ?? [],
                        'DS' => $ranking['ds'] ?? [],
                      ]
                    : [
                        'Hasil' => $ranking,
                      ];
            @endphp

            @if(empty($ranking))
                <div style="background:#fef3c7;border:1px solid #fbbf24;border-radius:12px;padding:20px;">
                    <div style="color:#92400e;">Tidak ada ranking tersimpan.</div>
                </div>
            @else
                @foreach($groups as $label => $list)
                    <div style="margin-bottom:20px;">
                        <div style="background:#fff7ed;padding:12px 16px;border-radius:10px;margin-bottom:12px;">
                            <span style="font-weight:800;color:#9a3412;font-size:16px;">{{ $label }}</span>
                        </div>

                        @if(empty($list))
                            <div style="background:#fef3c7;border:1px solid #fbbf24;border-radius:12px;padding:16px;color:#92400e;">
                                Data {{ $label }} tidak tersedia.
                            </div>
                        @else
                            <div style="overflow:auto;border:1px solid #fed7aa;border-radius:12px;">
                                <table style="width:100%;border-collapse:collapse;background:#fff;">
                                    <thead>
                                        <tr style="background:#ffedd5;">
                                            <th style="padding:16px;text-align:left;font-weight:700;color:#9a3412;border-bottom:2px solid #fed7aa;">No</th>
                                            <th style="padding:16px;text-align:left;font-weight:700;color:#9a3412;border-bottom:2px solid #fed7aa;">Penyakit</th>
                                            <th style="padding:16px;text-align:left;font-weight:700;color:#9a3412;border-bottom:2px solid #fed7aa;">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $idx => $row)
                                        @php
                                            $nama  = $row['nama'] ?? $row['nama_penyakit'] ?? $row['penyakit'] ?? '-';
                                            $kode  = $row['kode'] ?? $row['kode_penyakit'] ?? null;
                                            $nilai = $row['nilai'] ?? $row['score'] ?? null;
                                        @endphp
                                        <tr style="transition:background 0.2s;" onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='#fff'">
                                            <td style="padding:16px;border-bottom:1px solid #fed7aa;color:#78350f;font-weight:600;">
                                                {{ $idx + 1 }}
                                            </td>
                                            <td style="padding:16px;border-bottom:1px solid #fed7aa;color:#78350f;">
                                                <div style="font-weight:700;color:#92400e;">{{ $kode ? $kode.' - ' : '' }}{{ $nama }}</div>
                                            </td>
                                            <td style="padding:16px;border-bottom:1px solid #fed7aa;color:#78350f;font-weight:700;">
                                                {{ is_null($nilai) ? '-' : round((float)$nilai, 6) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
@endsection
