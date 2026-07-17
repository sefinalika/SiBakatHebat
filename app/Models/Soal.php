<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'nomor_soal',
        'teks',
        'bagian',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
    ];
}
