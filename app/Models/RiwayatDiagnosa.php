<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_diagnosas';

    protected $fillable = [
        'user_id',
        'payload',
        'diagnosa_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'diagnosa_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
