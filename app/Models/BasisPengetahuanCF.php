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
        return $this->belongsTo(Penyakit::class);
    }

    /**
     * Relasi ke tabel gejala
     */
    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
