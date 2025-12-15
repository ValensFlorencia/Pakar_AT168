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
    // ================== FORM DIAGNOSA ==================
    public function index()
    {
        // ambil semua gejala untuk ditampilkan di diagnosa.index
        $gejalas = Gejala::orderBy('kode_gejala')->get();

        return view('diagnosa.index', compact('gejalas'));
    }

    // ================== PROSES HASIL DIAGNOSA ==================
    public function hasil(Request $request)
    {
        // ------ 1. Ambil input gejala + CF user ------
        $request->validate([
            'gejala_id'   => 'required|array|min:1',
            'gejala_id.*' => 'exists:gejala,id',
            'cf_user'     => 'required|array',
            'cf_user.*'   => 'required|numeric|min:0|max:1',
        ]);

        $selected = [];
        foreach ($request->gejala_id as $i => $gid) {
            $cfUser = $request->cf_user[$i] ?? null;

            // kalau user belum pilih bobot, skip
            if ($cfUser === null || $cfUser === '') {
                continue;
            }

            $selected[] = [
                'gejala_id' => (int) $gid,
                'cf_user'   => (float) $cfUser,
            ];
        }

        if (empty($selected)) {
            return back()
                ->withErrors(['gejala_id' => 'Silakan pilih minimal satu gejala dan bobot CF-nya.'])
                ->withInput();
        }

        // ------ 2. Data gejala terpilih (untuk view + payload riwayat) ------
        $gejalaIds = collect($selected)->pluck('gejala_id')->all();

        $gejalaModels = Gejala::whereIn('id', $gejalaIds)
            ->orderBy('kode_gejala')
            ->get()
            ->keyBy('id');

        // bentuk data rapi untuk ditampilkan & disimpan
        $gejalaTerpilih = array_map(function ($item) use ($gejalaModels) {
            $g = $gejalaModels[$item['gejala_id']] ?? null;

            return [
                'gejala_id'    => $item['gejala_id'],
                'kode_gejala'  => $g?->kode_gejala,
                'nama_gejala'  => $g?->nama_gejala,
                'cf_user'      => $item['cf_user'],
            ];
        }, $selected);

        // ------ 3. Hitung CF & DS ------
        $hasilCF = $this->hitungCF($selected);
        $hasilDS = $this->hitungDS($selected);

        // urutkan dari terbesar
        $sortedCF = collect($hasilCF)->sortByDesc('nilai')->values()->all();
        $sortedDS = collect($hasilDS)->sortByDesc('nilai')->values()->all();

        // ------ 4. Ranking + hasil teratas + kesimpulan ------
        // (biar sederhana: pakai DS jika ada, kalau DS kosong pakai CF)
        $hasilTeratas = $sortedDS[0] ?? ($sortedCF[0] ?? null);

        $rankingPenyakit = [
            'cf' => $sortedCF,
            'ds' => $sortedDS,
        ];

        $kesimpulan = $hasilTeratas
            ? 'Ayam kemungkinan menderita ' . ($hasilTeratas['nama'] ?? 'Tidak diketahui') .
            ' (' . round(($hasilTeratas['nilai'] ?? 0) * 100, 2) . '%).'
            : 'Tidak ditemukan penyakit dominan berdasarkan gejala yang dipilih.';

        // ------ 5. SIMPAN RIWAYAT ------
        RiwayatDiagnosa::create([
            'user_id' => auth()->id(), // boleh null
            'judul'   => 'Hasil Diagnosa - ' . ($hasilTeratas['nama'] ?? 'Tidak diketahui'),
            'payload' => [
                'gejala_terpilih' => $gejalaTerpilih,
                'hasil_cf'        => $sortedCF,   // simpan yang sudah urut
                'hasil_ds'        => $sortedDS,   // simpan yang sudah urut
                'ranking'         => $rankingPenyakit,
                'kesimpulan'      => $kesimpulan,
            ],
            'diagnosa_at' => now(),
        ]);

        // ------ 6. TAMPILKAN HASIL ------
        return view('diagnosa.hasil', compact(
            'gejalaTerpilih',
            'hasilCF',
            'hasilDS',
            'rankingPenyakit',
            'kesimpulan'
        ));
    }

    /* ============================================================
     *  IMPLEMENTASI CF (sesuai tabel 3.7–3.16)
     * ============================================================
     */
    private function hitungCF(array $selected): array
    {
        // ambil semua basis CF yang berkaitan dengan gejala yang dipilih
        $gejalaIds = collect($selected)->pluck('gejala_id')->all();

        $basis = BasisPengetahuanCF::with('penyakit')
            ->whereIn('gejala_id', $gejalaIds)
            ->get()
            ->groupBy('penyakit_id');

        $hasil = [];

        foreach ($basis as $penyakitId => $rules) {
            $cfOld = null;

            foreach ($rules as $row) {
                // CF_pakar diambil dari basis_pengetahuan_cf (kolom cf_value)
                $cfPakar = $row->cf_value;

                // CF_user dari input pasien (dropdown “Yakin, Cukup Yakin, ...”)
                $cfUser = collect($selected)
                    ->firstWhere('gejala_id', $row->gejala_id)['cf_user'] ?? null;

                if ($cfUser === null) {
                    continue;
                }

                // ====== CF(H,E) = CF_user * CF_pakar (Tabel 3.8–3.14) ======
                $cfHE = $cfUser * $cfPakar;

                // ====== Kombinasi: CFcombine = CFold + CFnew * (1 - CFold) ======
                if ($cfOld === null) {
                    $cfOld = $cfHE;        // gejala pertama
                } else {
                    $cfOld = $cfOld + $cfHE * (1 - $cfOld);
                }
            }

            if ($cfOld !== null) {
                $hasil[] = [
                    'penyakit_id' => $penyakitId,
                    'kode'        => $rules->first()->penyakit->kode_penyakit,
                    'nama'        => $rules->first()->penyakit->nama_penyakit,
                    'nilai'       => $cfOld,
                    'persen'      => round($cfOld * 100, 2),
                ];
            }
        }

        return $hasil;
    }

    /* ============================================================
     *  IMPLEMENTASI DEMPSTER-SHAFER (tabel 3.19–3.21)
     * ============================================================
     */
    private function hitungDS(array $selected): array
    {
        $gejalaIds = collect($selected)->pluck('gejala_id')->all();

        // 1) Bangun mass function per gejala
        $massList = [];

        foreach ($gejalaIds as $gid) {
        $rows = BasisPengetahuanDS::where('gejala_id', $gid)->get();
        if ($rows->isEmpty()) continue;

        // ✅ TARUH DEBUG DI SINI


            $penyakitIds = $rows->pluck('penyakit_id')->unique()->sort()->values()->all();
            $subsetKey   = implode(',', $penyakitIds);

            $m      = (float) $rows->avg('ds_value');
            $m      = max(0.0, min(1.0, $m));
            $mTheta = 1 - $m;

            $massList[] = [
                $subsetKey => $m,
                'Θ'        => $mTheta,
            ];
        }


        if (empty($massList)) return [];

        // 2) Kombinasi iteratif
        $currentMass = array_shift($massList);
        foreach ($massList as $nextMass) {
            $currentMass = $this->combineMass($currentMass, $nextMass);
        }



        // ✅ 3) Frame of discernment: semua penyakit (Θ)
        $singletons = $this->toSingletonSimulasi($currentMass);


        // Ambil info penyakit
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
                'nilai'       => $val,
                'persen'      => round($val * 100, 2),
            ];
        }

        // optional: urutkan paling besar
        usort($hasil, fn($a,$b) => $b['nilai'] <=> $a['nilai']);

        return $hasil;
    }


    // ===== helper untuk Dempster’s rule: m12(Z) = (Σ m1(X)m2(Y)) / (1-K) =====
    private function combineMass(array $m1, array $m2): array
    {
        $result = [];
        $K      = 0; // konflik

        foreach ($m1 as $A => $m1v) {
            foreach ($m2 as $B => $m2v) {
                $intersection = $this->subsetIntersection($A, $B);

                if ($intersection === null) {
                    // X ∩ Y = ∅ → konflik
                    $K += $m1v * $m2v;
                } else {
                    $key           = $intersection;
                    $result[$key]  = ($result[$key] ?? 0) + $m1v * $m2v;
                }
            }
        }

        if ($K >= 1) {
            // full conflict, kembalikan apa adanya
            return $result;
        }

        $factor = 1 / (1 - $K);

        foreach ($result as $k => $v) {
            $result[$k] = $v * $factor;
        }

        return $result;
    }

    // A dan B = 'Θ' atau '1,5,7' (id penyakit dipisah koma)
    private function subsetIntersection(string $A, string $B): ?string
    {
        if ($A === 'Θ') {
            return $B;
        }
        if ($B === 'Θ') {
            return $A;
        }

        $a = array_filter(explode(',', $A));
        $b = array_filter(explode(',', $B));

        $inter = array_values(array_intersect($a, $b));

        if (empty($inter)) {
            return null;
        }

        sort($inter);
        return implode(',', $inter);
    }

    // distribusi {1,9}=0.1033 → 1 & 9 masing-masing +0.05165, dst.
    // BetP: distribusi semua subset + Θ ke singleton secara rata
    private function toPignistic(array $mass, array $frameIds): array
    {
        $single = [];

        // init semua penyakit agar yang tidak muncul tetap dapat porsi Θ
        foreach ($frameIds as $id) {
            $single[$id] = 0.0;
        }

        $thetaVal = $mass['Θ'] ?? 0.0;
        unset($mass['Θ']);

        // distribusi subset
        foreach ($mass as $key => $val) {
            $ids = array_filter(explode(',', $key));
            $n   = count($ids);
            if ($n === 0) continue;

            $share = $val / $n;
            foreach ($ids as $id) {
                if (!isset($single[$id])) $single[$id] = 0.0;
                $single[$id] += $share;
            }
        }

        // distribusi Θ rata ke semua penyakit pada frame
        $N = max(count($frameIds), 1);
        $thetaShare = $thetaVal / $N;

        foreach ($frameIds as $id) {
            $single[$id] += $thetaShare;
        }

        // normalisasi kecil biar total pas ~1 (optional tapi bagus)
        $sum = array_sum($single);
        if ($sum > 0) {
            foreach ($single as $id => $v) {
                $single[$id] = $v / $sum;
            }
        }

        return $single;
    }
    private function toSingletonSimulasi(array $mass): array
    {
        $single = [];

        foreach ($mass as $key => $val) {
            if ($key === 'Θ') continue; // ✅ sesuai simulasi kamu

            $ids = array_filter(explode(',', $key));
            $n = count($ids);
            if ($n === 0) continue;

            $share = $val / $n;
            foreach ($ids as $id) {
                $single[$id] = ($single[$id] ?? 0.0) + $share;
            }
        }

        return $single; // ❌ jangan dinormalisasi
    }



}
