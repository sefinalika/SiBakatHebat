<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Observasi extends Model
{
    protected $table = 'observasi';

    protected $fillable = [
        'peserta_id',
        'tanggal',
        'nama_guru',
        'nama_kepala_sekolah',
        'tahun_ajaran',
        'semester',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class, 'observasi_id');
    }

    public function hasilObservasi(): HasMany
    {
        return $this->hasMany(HasilObservasi::class, 'observasi_id');
    }
}
