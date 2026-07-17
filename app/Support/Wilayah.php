<?php

namespace App\Support;

class Wilayah
{
    /**
     * Data wilayah: [provinsi => [kota, ...]].
     *
     * @var array<string, string[]>|null
     */
    private static ?array $data = null;

    /**
     * @return array<string, string[]>
     */
    public static function all(): array
    {
        if (self::$data === null) {
            self::$data = require database_path('data/wilayah.php');
        }

        return self::$data;
    }

    /**
     * Daftar nama provinsi.
     *
     * @return string[]
     */
    public static function provinsiList(): array
    {
        return array_keys(self::all());
    }

    /**
     * Daftar kota untuk sebuah provinsi.
     *
     * @return string[]
     */
    public static function kotaList(string $provinsi): array
    {
        return self::all()[$provinsi] ?? [];
    }

    public static function provinsiValid(string $provinsi): bool
    {
        return array_key_exists($provinsi, self::all());
    }

    public static function kotaValid(string $provinsi, string $kota): bool
    {
        return in_array($kota, self::kotaList($provinsi), true);
    }
}
