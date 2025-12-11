<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();
        return view('gejala.index', compact('gejalas'));
    }

    // Form tambah gejala
    public function create()
    {
        // cari kode gejala terakhir, misal: G01, G02, ...
        $last = Gejala::orderBy('kode_gejala', 'desc')->first();

        if ($last) {
            // ambil angka di belakang huruf, misal "G07" -> 7
            $lastNumber = (int) substr($last->kode_gejala, 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // bikin kode baru, misal: G01, G02, G10, dst
        $newCode = 'G' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // kirim ke view
        return view('gejala.create', compact('newCode'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala',
            'nama_gejala' => 'required'
        ]);

        Gejala::create($request->all());

        return redirect()->route('gejala.index')
            ->with('success','Data gejala berhasil ditambahkan');
    }

    public function edit(Gejala $gejala)
    {
        return view('gejala.edit', compact('gejala'));
    }

    public function update(Request $request, Gejala $gejala)
    {
        $request->validate([
            'kode_gejala' => 'required|unique:gejala,kode_gejala,' . $gejala->id,
            'nama_gejala' => 'required'
        ]);

        $gejala->update($request->all());

        return redirect()->route('gejala.index')
            ->with('success','Data gejala berhasil diperbarui');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('gejala.index')
            ->with('success','Data gejala berhasil dihapus');
    }
}
