<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiagnosa;
use App\Models\Penyakit; // ✅ tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatDiagnosaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? null;

        $query = RiwayatDiagnosa::query()
            ->with('user')
            ->orderByDesc('diagnosa_at');

        if ($role === 'peternak') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('diagnosa_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('diagnosa_at', '<=', $request->end_date);
        }

        $riwayats = $query->paginate(10)->withQueryString();

        return view('riwayat_diagnosa.index', compact('riwayats'));
    }

    public function show(RiwayatDiagnosa $riwayat)
    {
        $user = Auth::user();
        $role = $user->role ?? null;

        if ($role === 'peternak' && (int) $riwayat->user_id !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $riwayat->load('user');

        // ✅ ambil data penyakit + solusi
        // (sesuaikan nama kolom kalau beda)
        $penyakits = Penyakit::select('kode_penyakit', 'nama_penyakit', 'solusi')->get();

        // ✅ map kode => solusi (dibersihin biar gak gagal match)
        $solusiMap = $penyakits->mapWithKeys(function ($p) {
            $kode = strtoupper(trim($p->kode_penyakit));
            return [$kode => $p->solusi];
        });

        return view('riwayat_diagnosa.show', compact('riwayat', 'solusiMap'));
    }

    public function destroy(RiwayatDiagnosa $riwayat)
    {
        $user = Auth::user();
        $role = $user->role ?? null;

        if ($role === 'pemilik') {
            $riwayat->delete();

            return redirect()->route('riwayat-diagnosa.index')
                ->with('success', 'Riwayat diagnosa berhasil dihapus.');
        }

        if ((int) $riwayat->user_id !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $riwayat->delete();

        return redirect()->route('riwayat-diagnosa.index')
            ->with('success', 'Riwayat diagnosa berhasil dihapus.');
    }
}
