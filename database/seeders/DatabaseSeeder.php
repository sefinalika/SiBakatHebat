<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KarakterSeeder::class,
            SoalSeeder::class,
        ]);

        $this->buatAkunStaf('admin@gmail.com', 'Super Admin', 'admin', 'ADMIN_PASSWORD');
        $this->buatAkunStaf('guru@gmail.com', 'Guru', 'guru', 'GURU_PASSWORD');
        $this->buatAkunStaf('sefinalika@gmail.com', 'Sefinalika', 'admin', 'ADMIN_PASSWORD');
        $this->buatAkunStaf('k.awangndaru@gmail.com', 'K. Awang Ndaru', 'admin', 'ADMIN_PASSWORD');
    }

    /**
     * Buat akun staf HANYA jika belum ada. Seeder ini dijalankan ulang setiap
     * deploy, jadi password akun yang sudah ada tidak boleh ditimpa.
     */
    private function buatAkunStaf(string $email, string $nama, string $role, string $envPassword): void
    {
        if (User::where('email', $email)->exists()) {
            $this->command?->info("Akun {$role} ({$email}) sudah ada — password dibiarkan apa adanya.");

            return;
        }

        $password = (string) env($envPassword, '');

        if ($password === '') {
            // Di produksi tidak boleh ada password default yang bisa ditebak.
            $password = app()->environment('production')
                ? Str::password(16, symbols: false)
                : 'password';
        }

        User::create([
            'name' => $nama,
            'username' => $email,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $this->command?->warn("Akun {$role} dibuat — username: {$email} | password: {$password}");
        $this->command?->warn('Catat password ini sekarang; seeder tidak akan menampilkannya lagi.');
    }
}
