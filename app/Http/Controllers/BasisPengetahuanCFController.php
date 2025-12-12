<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuanCF;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BasisPengetahuanCFController extends Controller
{
    // ✅ INDEX: tampil per penyakit saja (ada tombol detail)
    public function index()
    {
        $penyakits = Penyakit::withCount('basisCF')
            ->having('basis_c_f_count', '>', 0)
            ->orderBy('kode_penyakit')
            ->get();

        return view('basis_pengetahuan_cf.index', compact('penyakits'));
    }

    // ✅ DETAIL: tampil semua gejala + bobot untuk 1 penyakit
    public function detailPenyakitCF(Penyakit $penyakit)
    {
        $rules = BasisPengetahuanCF::with('gejala')
            ->where('penyakit_id', $penyakit->id)
            ->orderBy('gejala_id')
            ->get();

        return view('basis_pengetahuan_cf.detailPenyakitCF', compact('penyakit', 'rules'));
    }

    public function create()
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        // ✅ mapping penyakit_id => [gejala_id...]
        // cast ke string biar JS cocok 100%
        $existingMap = BasisPengetahuanCF::select('penyakit_id', 'gejala_id')
            ->get()
            ->groupBy('penyakit_id')
            ->map(fn ($rows) => $rows->pluck('gejala_id')->map(fn ($id) => (string) $id)->values())
            ->toArray();

        return view('basis_pengetahuan_cf.create', compact('penyakits', 'gejalas', 'existingMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakits,id',
            'gejalas'     => 'required|array|min:1',

            'gejalas.*.gejala_id' => [
                'required',
                'exists:gejala,id', // ✅ TABEL GEJALA = gejala
                'distinct',
                Rule::unique('basis_pengetahuan_cf', 'gejala_id')
                    ->where(fn ($q) => $q->where('penyakit_id', $request->penyakit_id)),
            ],

            'gejalas.*.cf_value' => 'required|numeric|min:0|max:1',
        ], [
            'gejalas.*.gejala_id.distinct' => 'Gejala tidak boleh diulang dalam satu penyakit.',
            'gejalas.*.gejala_id.unique'   => 'Gejala tersebut sudah terdaftar untuk penyakit ini.',
        ]);

        foreach ($request->gejalas as $item) {
            BasisPengetahuanCF::create([
                'penyakit_id' => $request->penyakit_id,
                'gejala_id'   => $item['gejala_id'],
                'cf_value'    => $item['cf_value'],
            ]);
        }

        return redirect()->route('basis_pengetahuan_cf.index')
            ->with('success', 'Basis Pengetahuan CF berhasil disimpan.');
    }

    public function edit(BasisPengetahuanCF $basis_cf)
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        return view('basis_pengetahuan_cf.edit', [
            'basisCF'   => $basis_cf,
            'penyakits' => $penyakits,
            'gejalas'   => $gejalas,
        ]);
    }

    public function update(Request $request, BasisPengetahuanCF $basis_cf)
    {
        $request->validate([
            'cf_value' => 'required|numeric|min:0|max:1',
        ]);

        $basis_cf->update([
            'cf_value' => $request->cf_value,
        ]);

        return redirect()->route('basis_pengetahuan_cf.index')
            ->with('success', 'Bobot CF berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $basis = BasisPengetahuanCF::findOrFail($id);
        $basis->delete();

        return redirect()->route('basis_pengetahuan_cf.index')
            ->with('success', 'Basis pengetahuan CF berhasil dihapus.');
    }
}
