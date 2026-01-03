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

        $user = auth()->user();
        $role = $user->role ?? null;

        $q = RiwayatDiagnosa::query();

        /**
         * ✅ Hak akses data:
         * - pemilik: lihat semua riwayat semua user
         * - pakar: (default aman) hanya lihat riwayat milik sendiri
         * - peternak: hanya lihat riwayat milik sendiri
         *
         * Kalau kamu mau pakar juga lihat semua, tinggal ubah kondisi ini:
         * if (!in_array($role, ['pemilik','pakar'])) { ... }
         */
        if ($role !== 'pemilik') {
            $q->where('user_id', $user->id);
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
        $user = auth()->user();
        $role = $user->role ?? null;

        /**
         * ✅ Hak akses:
         * - pemilik: boleh lihat detail semua riwayat
         * - pakar/peternak: hanya boleh lihat detail miliknya sendiri
         */
        if ($role !== 'pemilik' && $riwayat->user_id && $riwayat->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('riwayat_diagnosa.show', compact('riwayat'));
    }

    // hapus riwayat
    public function destroy(RiwayatDiagnosa $riwayat)
    {
        $user = auth()->user();
        $role = $user->role ?? null;

        /**
         * ✅ Hak akses hapus:
         * - pemilik: boleh hapus semua riwayat
         * - pakar/peternak: hanya boleh hapus riwayat miliknya sendiri
         * (kalau kamu mau peternak/pakar TIDAK boleh hapus sama sekali, bilang aja)
         */
        if ($role !== 'pemilik' && $riwayat->user_id && $riwayat->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $riwayat->delete();
        return redirect()->route('riwayat-diagnosa.index')->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
