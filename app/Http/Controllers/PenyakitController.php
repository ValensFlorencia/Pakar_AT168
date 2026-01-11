<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyakitController extends Controller
{
    /**
     * Tampilkan tabel list penyakit
     */
    public function index()
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();

        // ✅ ambil semua ID penyakit yang sudah dipakai di basis pengetahuan CF/DS
        $usedCf = DB::table('basis_pengetahuan_cf')->pluck('penyakit_id')->filter()->unique()->toArray();
        $usedDs = DB::table('basis_pengetahuan_ds')->pluck('penyakit_id')->filter()->unique()->toArray();

        $usedPenyakitIds = array_values(array_unique(array_merge($usedCf, $usedDs)));

        return view('penyakit.index', compact('penyakits', 'usedPenyakitIds'));
    }

    /**
     * Form tambah penyakit – kode otomatis
     */
    public function create()
    {
        // Ambil data terakhir
        $last = Penyakit::orderBy('id', 'desc')->first();

        // Generate kode baru (P01, P02, P03, ...)
        if ($last && $last->kode_penyakit) {
            $lastNumber = (int) substr($last->kode_penyakit, 1);
            $newCode = 'P' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newCode = 'P01';
        }

        return view('penyakit.create', compact('newCode'));
    }

    /**
     * Simpan penyakit baru – kode otomatis juga disini
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penyakit' => 'required',
            'deskripsi'     => 'required',
            'solusi'        => 'required',
        ]);

        // Generate kode penyakit otomatis
        $last = Penyakit::orderBy('id', 'desc')->first();

        if ($last && $last->kode_penyakit) {
            $lastNumber = (int) substr($last->kode_penyakit, 1);
            $kodeBaru   = 'P' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $kodeBaru = 'P01';
        }

        // Simpan penyakit baru
        Penyakit::create([
            'kode_penyakit' => $kodeBaru,
            'nama_penyakit' => $request->nama_penyakit,
            'deskripsi'     => $request->deskripsi,
            'solusi'        => $request->solusi,
        ]);

        return redirect()->route('penyakit.index')
            ->with('success', 'Penyakit berhasil ditambahkan.');
    }

    /**
     * Form edit penyakit
     */
    public function edit($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        return view('penyakit.edit', compact('penyakit'));
    }

    /**
     * Update data penyakit (kode tidak berubah)
     */
    public function update(Request $request, $id)
    {
        $penyakit = Penyakit::findOrFail($id);

        $request->validate([
            'nama_penyakit' => 'required',
            'deskripsi'     => 'required',
            'solusi'        => 'required',
        ]);

        $penyakit->update([
            'nama_penyakit' => $request->nama_penyakit,
            'deskripsi'     => $request->deskripsi,
            'solusi'        => $request->solusi,
        ]);

        return redirect()->route('penyakit.index')
            ->with('success', 'Penyakit berhasil diperbarui.');
    }

    /**
     * Hapus penyakit (blok jika sudah dipakai di basis pengetahuan/diagnosa)
     */
    public function destroy($id)
    {
        $penyakit = Penyakit::findOrFail($id);

        // ✅ blok hapus kalau penyakit sudah dipakai
        $isUsed = DB::table('basis_pengetahuan_cf')->where('penyakit_id', $penyakit->id)->exists()
               || DB::table('basis_pengetahuan_ds')->where('penyakit_id', $penyakit->id)->exists();

        if ($isUsed) {
            return redirect()
                ->route('penyakit.index')
                ->with('error', 'Penyakit tidak bisa dihapus karena sudah digunakan di basis pengetahuan/diagnosa.');
        }

        $penyakit->delete();

        return redirect()
            ->route('penyakit.index')
            ->with('success', 'Penyakit berhasil dihapus.');
    }
}
