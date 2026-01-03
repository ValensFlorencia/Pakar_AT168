<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\BasisPengetahuanCF;
use App\Models\BasisPengetahuanDS;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;

class DiagnosaController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();
        return view('diagnosa.index', compact('gejalas'));
    }

    public function hasil(Request $request)
    {
        $request->validate([
            'gejala_id'   => 'required|array|min:1',
            'gejala_id.*' => 'exists:gejala,id',
            'cf_user'     => 'required|array',
        ]);

        $gejalaIds = $request->input('gejala_id', []);
        $cfUserMap = $request->input('cf_user', []);

        $selected = [];
        foreach ($gejalaIds as $gid) {
            $cfUser = $cfUserMap[$gid] ?? null;
            if ($cfUser === null || $cfUser === '') continue;

            $cfUser = (float) $cfUser;
            if ($cfUser < 0 || $cfUser > 1) continue;

            $selected[] = [
                'gejala_id' => (int) $gid,
                'cf_user'   => $cfUser,
            ];
        }

        $selectedNonZero = array_filter($selected, fn($x) => ($x['cf_user'] ?? 0) > 0);
        if (empty($selectedNonZero)) {
            return back()
                ->withErrors(['gejala_id' => 'Bobot CF bernilai 0 berarti gejala tidak diyakini. Pilih minimal 1 gejala dengan bobot > 0.'])
                ->withInput();
        }

        // data untuk view
        $gejalaIdsSelected = collect($selected)->pluck('gejala_id')->all();
        $gejalaModels = Gejala::whereIn('id', $gejalaIdsSelected)->orderBy('kode_gejala')->get()->keyBy('id');

        $gejalaTerpilih = array_map(function ($item) use ($gejalaModels) {
            $g = $gejalaModels[$item['gejala_id']] ?? null;
            return [
                'gejala_id'   => $item['gejala_id'],
                'kode_gejala' => $g?->kode_gejala,
                'nama_gejala' => $g?->nama_gejala,
                'cf_user'     => $item['cf_user'],
            ];
        }, $selected);

        // hitung
        $hasilCF = $this->hitungCF($selectedNonZero);
        $hasilDS = $this->hitungDS($selectedNonZero); // ✅ DS simulasi (abaikan cf_user)

        $sortedCF = collect($hasilCF)->sortByDesc('nilai')->values()->all();
        $sortedDS = collect($hasilDS)->sortByDesc('nilai')->values()->all();

        $topDS = $sortedDS[0] ?? null;
        $topCF = $sortedCF[0] ?? null;

        $hasilTeratas = null;
        $metodeDominan = null;

        $TH = 0.6;

        if ($topDS && ($topDS['nilai'] ?? 0) >= $TH) {
            $hasilTeratas = $topDS;
            $metodeDominan = 'DS';
        } elseif ($topCF && ($topCF['nilai'] ?? 0) >= $TH) {
            $hasilTeratas = $topCF;
            $metodeDominan = 'CF';
        } else {
            $cand = collect([$topDS, $topCF])->filter()->sortByDesc('nilai')->first();
            $hasilTeratas = $cand;
            $metodeDominan = 'Belum Dominan';
        }

        $rankingPenyakit = [
            'cf' => $sortedCF,
            'ds' => $sortedDS,
        ];

        $top3 = !empty($sortedDS) ? array_slice($sortedDS, 0, 3) : array_slice($sortedCF, 0, 3);

        if (!$hasilTeratas) {
            $kesimpulan = 'Tidak ditemukan penyakit dominan berdasarkan gejala yang dipilih.';
        } else {
            $top3Text = collect($top3)->map(function ($x, $i) {
                $nama = $x['nama'] ?? 'Tidak diketahui';
                $persen = round(($x['nilai'] ?? 0) * 100, 2);
                return ($i + 1) . '. ' . $nama . ' (' . $persen . '%)';
            })->implode(', ');

            $kesimpulan =
                '3 kemungkinan penyakit teratas: ' . $top3Text . '. ' .
                'Kesimpulan utama: Ayam kemungkinan menderita ' . ($hasilTeratas['nama'] ?? 'Tidak diketahui') .
                ' (' . round(($hasilTeratas['nilai'] ?? 0) * 100, 2) . '%) — Metode: ' . $metodeDominan . '.';
        }

        RiwayatDiagnosa::create([
            'user_id' => auth()->id(),
            'judul'   => 'Hasil Diagnosa - ' . ($hasilTeratas['nama'] ?? 'Tidak diketahui'),
            'payload' => [
                'gejala_terpilih' => $gejalaTerpilih,
                'hasil_cf'        => $sortedCF,
                'hasil_ds'        => $sortedDS,
                'ranking'         => $rankingPenyakit,
                'kesimpulan'      => $kesimpulan,
                'metode_dominan'  => $metodeDominan,
            ],
            'diagnosa_at' => now(),
        ]);

        // biar di view gak tampil paragraf kesimpulan panjang
        $kesimpulanUntukView = null;

        return view('diagnosa.hasil', compact(
            'gejalaTerpilih',
            'hasilCF',
            'hasilDS',
            'rankingPenyakit'
        ))->with([
            'kesimpulan' => $kesimpulanUntukView,
            'metodeDominan' => $metodeDominan,
        ]);
    }

    private function hitungCF(array $selected): array
    {
        $cfUserByGejala = collect($selected)->pluck('cf_user', 'gejala_id');
        $gejalaIds = $cfUserByGejala->keys()->all();

        $basis = BasisPengetahuanCF::with('penyakit')
            ->whereIn('gejala_id', $gejalaIds)
            ->get()
            ->groupBy('penyakit_id');

        $hasil = [];

        foreach ($basis as $penyakitId => $rules) {
            $cfOld = null;

            foreach ($rules as $row) {
                $cfPakar = (float) $row->cf_value;
                $cfUser  = $cfUserByGejala->get($row->gejala_id);

                if ($cfUser === null) continue;

                $cfHE = ((float)$cfUser) * $cfPakar;

                $cfOld = ($cfOld === null)
                    ? $cfHE
                    : ($cfOld + $cfHE * (1 - $cfOld));
            }

            if ($cfOld !== null) {
                $p = $rules->first()->penyakit;

                $hasil[] = [
                    'penyakit_id' => $penyakitId,
                    'kode'        => $p->kode_penyakit,
                    'nama'        => $p->nama_penyakit,
                    'nilai'       => $cfOld,
                    'persen'      => round($cfOld * 100, 2),
                ];
            }
        }

        return $hasil;
    }

    // ==========================================================
    // ✅ DS SIMULASI PERSIS:
    // - abaikan cf_user
    // - m(gejala) = rata-rata ds_value (unik per penyakit)
    // - m(Θ)=1-m
    // - combine Dempster + K + normalisasi 1/(1-K)
    // - output singleton = bagi rata (tanpa normalisasi ulang)
    // ==========================================================
    private function hitungDS(array $selected): array
    {
        $massList = [];

        foreach ($selected as $item) {
            $gid = (int) $item['gejala_id'];

            // ambil semua penyakit terkait gejala ini
            // dan amankan jika ada duplikat: ambil nilai maksimum per penyakit_id
            $rows = BasisPengetahuanDS::where('gejala_id', $gid)
                ->get(['penyakit_id', 'ds_value'])
                ->groupBy('penyakit_id')
                ->map(function ($grp) {
                    return (float) $grp->max('ds_value');
                });

            if ($rows->isEmpty()) continue;

            $ids = $rows->keys()->map(fn($x) => (string)$x)->all();
            sort($ids);

            $sum = $rows->sum();
            $m = $sum / max(count($ids), 1);   // rata-rata seperti simulasi
            $m = max(0.0, min(1.0, $m));

            $subsetKey = implode(',', $ids);

            $massList[] = [
                $subsetKey => $m,
                'Θ'        => 1 - $m,
            ];
        }

        if (empty($massList)) return [];

        $currentMass = array_shift($massList);
        foreach ($massList as $nextMass) {
            $currentMass = $this->combineMass($currentMass, $nextMass);
        }

        // ubah mass gabungan ke singleton (bagi rata) — TANPA normalisasi ulang
        $singletons = $this->toSingletonSimulasi($currentMass);
        if (empty($singletons)) return [];

        $penyakitModels = Penyakit::whereIn('id', array_map('intval', array_keys($singletons)))
            ->get()
            ->keyBy('id');

        $hasil = [];
        foreach ($singletons as $pid => $val) {
            $p = $penyakitModels[(int)$pid] ?? null;
            if (!$p) continue;

            $hasil[] = [
                'penyakit_id' => (int)$pid,
                'kode'        => $p->kode_penyakit,
                'nama'        => $p->nama_penyakit,
                'nilai'       => (float)$val,
                'persen'      => round(((float)$val) * 100, 2),
            ];
        }

        usort($hasil, fn($a,$b) => $b['nilai'] <=> $a['nilai']);
        return $hasil;
    }

    private function combineMass(array $m1, array $m2): array
    {
        $result = [];
        $K = 0.0;

        foreach ($m1 as $A => $m1v) {
            foreach ($m2 as $B => $m2v) {
                $intersection = $this->subsetIntersection((string)$A, (string)$B);

                if ($intersection === null) {
                    $K += ((float)$m1v) * ((float)$m2v);
                } else {
                    $result[$intersection] = ($result[$intersection] ?? 0.0) + ((float)$m1v) * ((float)$m2v);
                }
            }
        }

        $den = 1.0 - $K;
        if ($den <= 0) {
            return ['Θ' => 1.0];
        }

        $factor = 1 / $den;
        foreach ($result as $k => $v) {
            $result[$k] = $v * $factor;
        }

        if (!isset($result['Θ'])) $result['Θ'] = 0.0;

        return $result;
    }

    private function subsetIntersection(string $A, string $B): ?string
    {
        if ($A === 'Θ') return $B;
        if ($B === 'Θ') return $A;

        $a = array_filter(explode(',', $A));
        $b = array_filter(explode(',', $B));

        $inter = array_values(array_intersect($a, $b));
        if (empty($inter)) return null;

        sort($inter);
        return implode(',', $inter);
    }

    private function toSingletonSimulasi(array $mass): array
    {
        $single = [];

        foreach ($mass as $key => $val) {
            if ($key === 'Θ') continue;

            $ids = array_filter(explode(',', (string)$key));
            $n = count($ids);
            if ($n === 0) continue;

            $share = ((float)$val) / $n;
            foreach ($ids as $id) {
                $single[$id] = ($single[$id] ?? 0.0) + $share;
            }
        }

        // ✅ IMPORTANT: jangan normalisasi ulang.
        // simulasi kamu membiarkan sisa massa di Θ.
        return $single;
    }
}
