<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasisPengetahuanDS extends Model
{
    use HasFactory;

    protected $table = 'basis_pengetahuan_ds';

    protected $fillable = [
        'penyakit_id',
        'gejala_id',
        'ds_value',
    ];

    public function penyakit()
    {
        return $this->belongsTo(\App\Models\Penyakit::class, 'penyakit_id');
    }

    public function gejala()
    {
        return $this->belongsTo(\App\Models\Gejala::class, 'gejala_id');
    }
    public function basisDS()
    {
        return $this->hasMany(\App\Models\BasisPengetahuanDS::class, 'penyakit_id');
    }



}
