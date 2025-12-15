<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiagnosa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatDiagnosaController extends Controller
{
    // halaman daftar riwayat
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $q = RiwayatDiagnosa::query();
        $riwayats = $q->paginate(10)->appends($request->query());


        // kalau pakai login dan mau filter per user:
        if (auth()->check()) {
            $q->where('user_id', auth()->id());
        }

        // FILTER PERIODE (pakai diagnosa_at, fallback created_at)
        if ($request->filled('start_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $q->where(function ($w) use ($start) {
                $w->where('diagnosa_at', '>=', $start)
                ->orWhere(function ($w2) use ($start) {
                    $w2->whereNull('diagnosa_at')
                        ->where('created_at', '>=', $start);
                });
            });
        }

        if ($request->filled('end_date')) {
            $end = Carbon::parse($request->end_date)->endOfDay();
            $q->where(function ($w) use ($end) {
                $w->where('diagnosa_at', '<=', $end)
                ->orWhere(function ($w2) use ($end) {
                    $w2->whereNull('diagnosa_at')
                        ->where('created_at', '<=', $end);
                });
            });
        }

        $riwayats = $q->orderByDesc('diagnosa_at')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query()); // ✅ biar pagination tidak hilang filternya

        return view('riwayat_diagnosa.index', compact('riwayats'));
    }
    // detail 1 riwayat
    public function show(RiwayatDiagnosa $riwayat)
    {
        // jika pakai login, amankan akses
        if (auth()->check() && $riwayat->user_id && $riwayat->user_id !== auth()->id()) {
            abort(403);
        }

        return view('riwayat_diagnosa.show', compact('riwayat'));
    }

    // (opsional) hapus riwayat
    public function destroy(RiwayatDiagnosa $riwayat)
    {
        if (auth()->check() && $riwayat->user_id && $riwayat->user_id !== auth()->id()) {
            abort(403);
        }

        $riwayat->delete();
        return redirect()->route('riwayat-diagnosa.index')->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
