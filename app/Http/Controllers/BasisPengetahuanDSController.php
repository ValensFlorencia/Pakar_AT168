<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuanDS;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BasisPengetahuanDSController extends Controller
{
    // ✅ INDEX: tampil per penyakit saja (ada tombol detail)
    public function index()
    {
        $penyakits = Penyakit::withCount('basisDS')
            ->having('basis_d_s_count', '>', 0)
            ->orderBy('kode_penyakit')
            ->get();

        return view('basis_pengetahuan_ds.index', compact('penyakits'));

    }

    // ✅ DETAIL: tampil semua gejala + bobot untuk 1 penyakit
    public function detailPenyakitDS(Penyakit $penyakit)
    {
        $rules = BasisPengetahuanDS::with('gejala')
            ->where('penyakit_id', $penyakit->id)
            ->orderBy('gejala_id')
            ->get();

        return view('basis_pengetahuan_ds.detail', compact('penyakit', 'rules'));
    }

    public function create()
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        // mapping penyakit_id => [gejala_id...]
        $existingMap = BasisPengetahuanDS::select('penyakit_id', 'gejala_id')
            ->get()
            ->groupBy('penyakit_id')
            ->map(fn ($rows) => $rows->pluck('gejala_id')->map(fn ($id) => (string) $id)->values())
            ->toArray();

        return view('basis_pengetahuan_ds.create', compact('penyakits', 'gejalas', 'existingMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakits,id',
            'gejalas'     => 'required|array|min:1',

            'gejalas.*.gejala_id' => [
                'required',
                'exists:gejala,id',
                'distinct',
                Rule::unique('basis_pengetahuan_ds', 'gejala_id')
                    ->where(fn ($q) => $q->where('penyakit_id', $request->penyakit_id)),
            ],

            'gejalas.*.ds_value' => 'required|numeric|min:0|max:1',
        ], [
            'gejalas.*.gejala_id.distinct' => 'Gejala tidak boleh diulang dalam satu penyakit.',
            'gejalas.*.gejala_id.unique'   => 'Gejala tersebut sudah terdaftar untuk penyakit ini.',
        ]);

        foreach ($request->gejalas as $item) {
            BasisPengetahuanDS::create([
                'penyakit_id' => $request->penyakit_id,
                'gejala_id'   => $item['gejala_id'],
                'ds_value'    => $item['ds_value'],
            ]);
        }

        return redirect()->route('basis_pengetahuan_ds.index')
            ->with('success', 'Basis Pengetahuan DS berhasil disimpan.');
    }

    public function edit(BasisPengetahuanDS $basis_ds)
    {
        return view('basis_pengetahuan_ds.edit', [
            'basisDS' => $basis_ds,
        ]);
    }

    // ✅ edit hanya bobot (penyakit & gejala tidak berubah)
    public function update(Request $request, BasisPengetahuanDS $basis_ds)
    {
        $request->validate([
            'ds_value' => 'required|numeric|min:0|max:1',
        ]);

        $basis_ds->update([
            'ds_value' => $request->ds_value,
        ]);

        return redirect()->route('basis_pengetahuan_ds.index')
            ->with('success', 'Bobot DS berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $basis = BasisPengetahuanDS::findOrFail($id);
        $basis->delete();

        return redirect()->route('basis_pengetahuan_ds.index')
            ->with('success', 'Basis pengetahuan DS berhasil dihapus.');
    }
}
