<?php

namespace App\Support;

class KategoriWarna
{
    /**
     * Metadata warna kategori (PRD 3.4).
     *
     * @var array<string, array{label:string, hex:string}>
     */
    public const META = [
        'hitam' => ['label' => 'Sangat Lemah', 'hex' => '#1f2937'],
        'abu' => ['label' => 'Lemah', 'hex' => '#9ca3af'],
        'hijau' => ['label' => 'Sedang', 'hex' => '#22c55e'],
        'kuning' => ['label' => 'Kuat', 'hex' => '#eab308'],
        'merah' => ['label' => 'Sangat Kuat', 'hex' => '#ef4444'],
    ];

    public static function hex(?string $kategori): string
    {
        return self::META[$kategori]['hex'] ?? '#64748b';
    }

    public static function label(?string $kategori): string
    {
        return self::META[$kategori]['label'] ?? '-';
    }

    /**
     * Untuk legenda: [['key'=>, 'label'=>, 'hex'=>], ...] urut hitam..merah.
     *
     * @return array<int, array{key:string, label:string, hex:string}>
     */
    public static function legenda(): array
    {
        $out = [];
        foreach (self::META as $key => $m) {
            $out[] = ['key' => $key, 'label' => $m['label'], 'hex' => $m['hex']];
        }

        return $out;
    }

    /**
     * Label dimensi untuk pengelompokan grafik.
     */
    public static function labelDimensi(?string $dimensi): string
    {
        return match ($dimensi) {
            'bakat' => 'Kinerja Bakat — Karsa',
            'akal' => 'Kinerja Akal — Cipta',
            'hati' => 'Kinerja Hati — Rasa',
            default => 'Karakter TB-40',
        };
    }
}
