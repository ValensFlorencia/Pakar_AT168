<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GejalaController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();

        // ✅ ambil semua ID gejala yang sudah dipakai di basis pengetahuan CF/DS
        $usedCf = DB::table('basis_pengetahuan_cf')->pluck('gejala_id')->filter()->unique()->toArray();
        $usedDs = DB::table('basis_pengetahuan_ds')->pluck('gejala_id')->filter()->unique()->toArray();

        $usedGejalaIds = array_values(array_unique(array_merge($usedCf, $usedDs)));

        return view('gejala.index', compact('gejalas', 'usedGejalaIds'));
    }

    // Form tambah gejala
    public function create()
    {
        $last = Gejala::orderBy('kode_gejala', 'desc')->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_gejala, 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newCode = 'G' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return view('gejala.create', compact('newCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala',
            'nama_gejala' => 'required',
        ]);

        Gejala::create($request->all());

        return redirect()
            ->route('gejala.index')
            ->with('success', 'Data gejala berhasil ditambahkan');
    }

    public function edit(Gejala $gejala)
    {
        // (opsional) kalau mau blok edit saat sudah dipakai, bisa cek di sini juga.
        return view('gejala.edit', compact('gejala'));
    }

    public function update(Request $request, Gejala $gejala)
    {
        // ✅ blok update kalau gejala sudah dipakai
        $isUsed = DB::table('basis_pengetahuan_cf')->where('gejala_id', $gejala->id)->exists()
               || DB::table('basis_pengetahuan_ds')->where('gejala_id', $gejala->id)->exists();

        if ($isUsed) {
            return redirect()
                ->route('gejala.index')
                ->with('error', 'Gejala tidak bisa diubah karena sudah digunakan di basis pengetahuan/diagnosa.');
        }

        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala,' . $gejala->id,
            'nama_gejala' => 'required',
        ]);

        $gejala->update($request->all());

        return redirect()
            ->route('gejala.index')
            ->with('success', 'Data gejala berhasil diperbarui');
    }

    public function destroy(Gejala $gejala)
    {
        // ✅ blok hapus kalau gejala sudah dipakai
        $isUsed = DB::table('basis_pengetahuan_cf')->where('gejala_id', $gejala->id)->exists()
               || DB::table('basis_pengetahuan_ds')->where('gejala_id', $gejala->id)->exists();

        if ($isUsed) {
            return redirect()
                ->route('gejala.index')
                ->with('error', 'Gejala tidak bisa dihapus karena sudah digunakan di basis pengetahuan/diagnosa.');
        }

        $gejala->delete();

        return redirect()
            ->route('gejala.index')
            ->with('success', 'Data gejala berhasil dihapus');
    }
}
