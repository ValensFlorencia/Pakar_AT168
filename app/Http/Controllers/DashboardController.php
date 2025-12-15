<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\BasisPengetahuanCF;
use App\Models\BasisPengetahuanDS;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGejala   = Gejala::count();
        $totalPenyakit = Penyakit::count();

        $totalRule = BasisPengetahuanCF::count()
                   + BasisPengetahuanDS::count();

        return view('dashboard', compact(
            'totalGejala',
            'totalPenyakit',
            'totalRule'
        ));
    }
}
