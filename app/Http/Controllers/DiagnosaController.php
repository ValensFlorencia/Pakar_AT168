<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\BasisPengetahuanCF;
use App\Models\BasisPengetahuanDS;
use App\Models\Penyakit;
use Illuminate\Http\Request;

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
        // gejala_id[] -> [1, 3, 5]
        // cf_user[]   -> [0.8, 1, 0.6]
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

        // ------ 2. Hitung CF & DS ------
        $hasilCF = $this->hitungCF($selected);
        $hasilDS = $this->hitungDS($selected);

        // urutkan dari terbesar
        $sortedCF = collect($hasilCF)->sortByDesc('nilai')->values()->all();
        $sortedDS = collect($hasilDS)->sortByDesc('nilai')->values()->all();

        return view('diagnosa.hasil', [
            'gejala_terpilih' => $selected,
            'hasilCF'         => $sortedCF,
            'hasilDS'         => $sortedDS,
        ]);
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

        // 1. Bangun mass function m_i untuk tiap gejala
        $massList = [];

        foreach ($gejalaIds as $gid) {
            $rows = BasisPengetahuanDS::with('penyakit')
                ->where('gejala_id', $gid)
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            // himpunan penyakit yang terkait dengan gejala ini, misal {1,5,7,8,9}
            $penyakitIds = $rows->pluck('penyakit_id')->unique()->sort()->all();
            $subsetKey   = implode(',', $penyakitIds);

            // m = rata-rata bobot DS gejala tersebut
            $m      = (float) $rows->avg('ds_value');
            $mTheta = 1 - $m;

            $massList[] = [
                $subsetKey => $m,
                'Θ'        => $mTheta,
            ];
        }

        if (empty($massList)) {
            return [];
        }

        // 2. Kombinasi mass secara iteratif pakai Dempster’s rule
        $currentMass = array_shift($massList);

        foreach ($massList as $nextMass) {
            $currentMass = $this->combineMass($currentMass, $nextMass);
        }

        // 3. Distribusikan semua subset menjadi singleton penyakit
        $singletons = $this->toSingleton($currentMass);

        // Ambil informasi penyakit
        $penyakitModels = Penyakit::whereIn('id', array_keys($singletons))
            ->get()
            ->keyBy('id');

        $hasil = [];
        foreach ($singletons as $pid => $val) {
            $p = $penyakitModels[$pid] ?? null;
            if (! $p) {
                continue;
            }

            $hasil[] = [
                'penyakit_id' => $pid,
                'kode'        => $p->kode_penyakit,
                'nama'        => $p->nama_penyakit,
                'nilai'       => $val,
                'persen'      => round($val * 100, 2),
            ];
        }

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
    private function toSingleton(array $mass): array
    {
        $single = [];

        foreach ($mass as $key => $val) {
            if ($key === 'Θ') {
                continue;
            }

            $ids = array_filter(explode(',', $key));
            $n   = max(count($ids), 1);

            foreach ($ids as $id) {
                $single[$id] = ($single[$id] ?? 0) + $val / $n;
            }
        }

        return $single;
    }
}
