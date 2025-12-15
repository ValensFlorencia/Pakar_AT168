<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatDiagnosa extends Model
{
    protected $table = 'riwayat_diagnosas';

    protected $fillable = [
        'user_id',
        'judul',
        'payload',
        'diagnosa_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'diagnosa_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
