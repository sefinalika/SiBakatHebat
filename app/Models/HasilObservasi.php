<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilObservasi extends Model
{
    protected $table = 'hasil_observasi';

    protected $fillable = [
        'observasi_id',
        'karakter_id',
        'skor',
        'kategori',
    ];

    protected $casts = [
        'karakter_id' => 'integer',
        'skor' => 'decimal:2',
    ];

    public function observasi(): BelongsTo
    {
        return $this->belongsTo(Observasi::class, 'observasi_id');
    }

    public function karakter(): BelongsTo
    {
        return $this->belongsTo(Karakter::class, 'karakter_id');
    }
}
