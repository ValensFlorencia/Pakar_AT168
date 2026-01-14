<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiagnosa;
use App\Models\User;

// ✅ tambahin ini
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\BasisPengetahuanCF;
use App\Models\BasisPengetahuanDS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $auth = Auth::user();
        $role = $auth->role ?? 'peternak';

        // =========================
        // FILTER PERIODE
        // =========================
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $end   = $request->end_date   ? Carbon::parse($request->end_date)->endOfDay()   : null;

        // =========================
        // FILTER USER (KHUSUS OWNER/PAKAR)
        // =========================
        $selectedUserId = $request->get('user_id');
        $selectedUserId = ($selectedUserId === 'all' || $selectedUserId === '' || $selectedUserId === null)
            ? null
            : (int) $selectedUserId;

        $userOptions = collect();
        if (in_array($role, ['pemilik', 'pakar'])) {
            // kalau mau hanya peternak: ->where('role','peternak')
            $userOptions = User::orderBy('name')->get(['id','name','role']);
        }

        // =========================
        // QUERY RIWAYAT UNTUK TOP PENYAKIT
        // =========================
        $qTop = RiwayatDiagnosa::query();

        if ($role === 'peternak') {
            $qTop->where('user_id', $auth->id);
        } else {
            if ($selectedUserId) {
                $qTop->where('user_id', $selectedUserId);
            }
        }

        if ($start && $end) {
            $qTop->where(function ($q) use ($start, $end) {
                $q->whereBetween('diagnosa_at', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->whereNull('diagnosa_at')
                         ->whereBetween('created_at', [$start, $end]);
                  });
            });
        } elseif ($start) {
            $qTop->where(function ($q) use ($start) {
                $q->where('diagnosa_at', '>=', $start)
                  ->orWhere(function ($q2) use ($start) {
                      $q2->whereNull('diagnosa_at')
                         ->where('created_at', '>=', $start);
                  });
            });
        } elseif ($end) {
            $qTop->where(function ($q) use ($end) {
                $q->where('diagnosa_at', '<=', $end)
                  ->orWhere(function ($q2) use ($end) {
                      $q2->whereNull('diagnosa_at')
                         ->where('created_at', '<=', $end);
                  });
            });
        }

        $riwayatsTop = $qTop->latest()->get();

        // =========================
        // STATISTIK PETERNak
        // =========================
        $totalRiwayatSaya = 0;
        $diagnosaBulanIni = 0;
        $lastDiagnosaAt   = null;

        if ($role === 'peternak') {
            $qMine = RiwayatDiagnosa::where('user_id', $auth->id);

            $totalRiwayatSaya = $qMine->count();

            $diagnosaBulanIni = (clone $qMine)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $lastDiagnosaAt = (clone $qMine)->max('diagnosa_at')
                ?? (clone $qMine)->max('created_at');
        }

        // =========================
        // STATISTIK PAKAR/PEMILIK
        // =========================
        $totalGejala   = 0;
        $totalPenyakit = 0;
        $totalRule     = 0;

        if (in_array($role, ['pemilik','pakar'])) {
            $totalGejala   = Gejala::count();
            $totalPenyakit = Penyakit::count();

            // total rule = CF + DS (kalau tabel DS/CF ada)
            $totalRule = 0;

            if (class_exists(\App\Models\BasisPengetahuanCF::class)) {
                $totalRule += BasisPengetahuanCF::count();
            }
            if (class_exists(\App\Models\BasisPengetahuanDS::class)) {
                $totalRule += BasisPengetahuanDS::count();
            }
        }

        return view('dashboard', compact(
            'role',
            'riwayatsTop',
            'userOptions',
            'selectedUserId',

            'totalRiwayatSaya',
            'diagnosaBulanIni',
            'lastDiagnosaAt',

            'totalGejala',
            'totalPenyakit',
            'totalRule'
        ));
    }
}
