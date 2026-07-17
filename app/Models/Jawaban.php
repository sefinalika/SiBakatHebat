<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jawaban extends Model
{
    protected $table = 'jawaban';

    protected $fillable = [
        'observasi_id',
        'nomor_soal',
        'nilai',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'nilai' => 'integer',
    ];

    public function observasi(): BelongsTo
    {
        return $this->belongsTo(Observasi::class, 'observasi_id');
    }
}
