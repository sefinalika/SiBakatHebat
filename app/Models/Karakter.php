<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karakter extends Model
{
    protected $table = 'karakter';

    // id ditetapkan manual (1-40), bukan auto-increment.
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'kode',
        'nama_karakter',
        'nama_arab',
        'terjemahan',
        'label_diri',
        'definisi',
        'dimensi',
        'kelompok',
        'tipe',
        'profesi',
        'jurusan',
        'sifat_tercela_melalaikan',
        'cara_memperbaiki_melalaikan',
        'sifat_tercela_berlebihan',
        'cara_memperbaiki_berlebihan',
        'nomor_soal',
        'urut_abjad',
        'urut_grafik',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'urut_abjad' => 'integer',
        'urut_grafik' => 'integer',
    ];

    public function hasilObservasi(): HasMany
    {
        return $this->hasMany(HasilObservasi::class, 'karakter_id');
    }
}
