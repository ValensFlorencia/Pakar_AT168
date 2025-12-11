<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasisPengetahuanDS extends Model
{
    use HasFactory;

    // Nama tabel (kalau sama persis, sebenarnya bisa di-skip, tapi biar jelas)
    protected $table = 'basis_pengetahuan_ds';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'penyakit_id',
        'gejala_id',
        'ds_value',
    ];

    // Casting kalau mau dipastikan numeric
    protected $casts = [
        'ds_value' => 'float',
    ];

    // Relasi ke penyakit
    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    // Relasi ke gejala
    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
