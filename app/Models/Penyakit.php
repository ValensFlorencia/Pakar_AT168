<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $table = 'penyakits';

    protected $fillable = [
        'kode_penyakit',
        'nama_penyakit',
        'deskripsi',
        'solusi',
    ];
    public function basisCF()
    {
        return $this->hasMany(\App\Models\BasisPengetahuanCF::class, 'penyakit_id');
    }
    public function basisDS()
    {
        return $this->hasMany(\App\Models\BasisPengetahuanDS::class, 'penyakit_id');
    }

}
