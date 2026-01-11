<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\BasisPengetahuanCF;
use App\Models\BasisPengetahuanDS;
use App\Models\RiwayatDiagnosa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ?? 'peternak';

        // default value biar aman
        $totalGejala = 0;
        $totalPenyakit = 0;
        $totalRule = 0;

        $totalRiwayatSaya = 0;
        $diagnosaBulanIni = 0;
        $lastDiagnosaAt = null;

        if ($role === 'peternak') {
            $totalRiwayatSaya = RiwayatDiagnosa::where('user_id', $user->id)->count();

            $diagnosaBulanIni = RiwayatDiagnosa::where('user_id', $user->id)
                ->whereMonth('diagnosa_at', now()->month)
                ->whereYear('diagnosa_at', now()->year)
                ->count();

            $lastDiagnosaAt = RiwayatDiagnosa::where('user_id', $user->id)
                ->latest('diagnosa_at')
                ->value('diagnosa_at');
        } else {
            $totalGejala   = Gejala::count();
            $totalPenyakit = Penyakit::count();
            $totalRule =
                BasisPengetahuanCF::count() +
                BasisPengetahuanDS::count();
        }

        return view('dashboard', compact(
            'totalGejala',
            'totalPenyakit',
            'totalRule',
            'totalRiwayatSaya',
            'diagnosaBulanIni',
            'lastDiagnosaAt'
        ));
    }
}
