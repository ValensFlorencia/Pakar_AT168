<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuanDS;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BasisPengetahuanDSController extends Controller
{
    public function index()
    {
        // sama seperti CF: load relasi penyakit & gejala
        $basisDS = BasisPengetahuanDS::with(['penyakit', 'gejala'])->get();

        return view('basis_pengetahuan_ds.index', compact('basisDS'));
    }

    public function create()
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        return view('basis_pengetahuan_ds.create', compact('penyakits', 'gejalas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakits,id',
            'gejalas'     => 'required|array',

            // VALIDASI GEJALA
            'gejalas.*.gejala_id' => [
                'required',
                'exists:gejala,id',
                'distinct',   // tidak boleh dobel dalam 1 form
                Rule::unique('basis_pengetahuan_ds', 'gejala_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('penyakit_id', $request->penyakit_id);
                    }),
            ],

            // VALIDASI BOBOT DS
            'gejalas.*.ds_value'  => 'required|numeric|min:0|max:1',
        ], [
            'gejalas.*.gejala_id.distinct' => 'Gejala tidak boleh diulang dalam satu penyakit.',
            'gejalas.*.gejala_id.unique'   => 'Gejala tersebut sudah terdaftar untuk penyakit ini (DS).',
        ]);

        foreach ($request->gejalas as $item) {
            BasisPengetahuanDS::create([
                'penyakit_id' => $request->penyakit_id,
                'gejala_id'   => $item['gejala_id'],
                'ds_value'    => $item['ds_value'],   // pastikan nama kolom di tabel DS
            ]);
        }

        return redirect()
            ->route('basis_pengetahuan_ds.index')
            ->with('success', 'Basis Pengetahuan DS berhasil disimpan.');
    }

    public function edit(BasisPengetahuanDS $basis_d)
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        return view('basis_pengetahuan_ds.edit', [
            'basisDS'   => $basis_d,
            'penyakits' => $penyakits,
            'gejalas'   => $gejalas,
        ]);
    }

    public function update(Request $request, BasisPengetahuanDS $basis_d)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakits,id',
            'gejala_id'   => [
                'required',
                'exists:gejala,id',
                Rule::unique('basis_pengetahuan_ds', 'gejala_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('penyakit_id', $request->penyakit_id);
                    })
                    ->ignore($basis_d->id),
            ],
            'ds_value'    => 'required|numeric|min:0|max:1',
        ], [
            'gejala_id.unique' => 'Gejala ini sudah terdaftar untuk penyakit tersebut (DS).',
        ]);

        $basis_d->update([
            'penyakit_id' => $request->penyakit_id,
            'gejala_id'   => $request->gejala_id,
            'ds_value'    => $request->ds_value,
        ]);

        return redirect()
            ->route('basis_pengetahuan_ds.index')
            ->with('success', 'Basis Pengetahuan DS berhasil diperbarui.');
    }

    public function destroy(BasisPengetahuanDS $basis_d)
    {
        $basis_d->delete();

        return redirect()
            ->route('basis_pengetahuan_ds.index')
            ->with('success', 'Basis DS berhasil dihapus.');
    }
}
