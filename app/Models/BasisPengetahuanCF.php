<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasisPengetahuanCF extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'basis_pengetahuan_cf';

    // Kolom yang boleh diisi
    protected $fillable = [
        'penyakit_id',
        'gejala_id',
        'cf_value',
    ];

    /**
     * Relasi ke tabel penyakit
     */

    public function penyakit()
    {
        return $this->belongsTo(\App\Models\Penyakit::class, 'penyakit_id');
    }

    public function gejala()
    {
        return $this->belongsTo(\App\Models\Gejala::class, 'gejala_id');
    }

}
