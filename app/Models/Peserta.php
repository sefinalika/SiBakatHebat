<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nama_sekolah',
        'kelas',
        'provinsi',
        'kota',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Umur (tahun) dihitung otomatis dari tanggal lahir.
     */
    public function umur(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function observasi(): HasMany
    {
        return $this->hasMany(Observasi::class, 'peserta_id');
    }
}
