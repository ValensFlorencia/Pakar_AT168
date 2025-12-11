<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuanCF;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class BasisPengetahuanCFController extends Controller
{
    public function index()
    {
        $basisCF = BasisPengetahuanCF::with(['penyakit', 'gejala'])->get();

        return view('basis_pengetahuan_cf.index', compact('basisCF'));
    }


    public function create()
    {
        $penyakits = Penyakit::orderBy('kode_penyakit')->get();
        $gejalas   = Gejala::orderBy('kode_gejala')->get();

        return view('basis_pengetahuan_cf.create', compact('penyakits','gejalas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required|exists:penyakits,id',   // sesuaikan dengan nama tabelmu
            'gejalas'     => 'required|array',

            // VALIDASI GEJALA
            'gejalas.*.gejala_id' => [
                'required',
                'exists:gejala,id',    // sesuaikan: table "gejala"
                'distinct',            // ❌ tidak boleh sama dalam 1 form
                Rule::unique('basis_pengetahuan_cf', 'gejala_id') // ❌ tidak boleh sama di DB
                    ->where(function ($query) use ($request) {
                        return $query->where('penyakit_id', $request->penyakit_id);
                    }),
            ],

            // VALIDASI BOBOT
            'gejalas.*.cf_value'  => 'required|numeric|min:0|max:1',
        ], [
            // pesan error custom biar lebih jelas (opsional)
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

        return redirect()
            ->route('basis_pengetahuan_cf.index')
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
            'penyakit_id' => 'required|exists:penyakits,id',   // sesuaikan nama tabel
            'gejala_id'   => [
                'required',
                'exists:gejala,id',                           // sesuaikan nama tabel
                Rule::unique('basis_pengetahuan_cf', 'gejala_id')
                    ->where(function ($query) use ($request) {
                        return $query->where('penyakit_id', $request->penyakit_id);
                    })
                    ->ignore($basis_cf->id), // abaikan baris yang sedang diedit
            ],
            'cf_value'    => 'required|numeric|min:0|max:1',
        ], [
            'gejala_id.unique' => 'Gejala ini sudah terdaftar untuk penyakit tersebut.',
        ]);

        $basis_cf->update([
            'penyakit_id' => $request->penyakit_id,
            'gejala_id'   => $request->gejala_id,
            'cf_value'    => $request->cf_value,
        ]);

        return redirect()
            ->route('basis_cf.index')
            ->with('success', 'Basis Pengetahuan CF berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $basis = BasisPengetahuanCF::findOrFail($id);

        $basis->delete();

        return redirect()
            ->route('basis_pengetahuan_cf.index')
            ->with('success', 'Basis pengetahuan CF berhasil dihapus.');
    }

}
