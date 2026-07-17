<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bila APP_URL memakai HTTPS (mis. Codespaces / hosting), paksa semua URL
        // yang dihasilkan mengikuti APP_URL — mencegah port internal (:8000) ikut
        // di redirect saat berada di belakang proxy.
        $appUrl = (string) config('app.url');
        if (str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
            URL::forceRootUrl($appUrl);
        }

        $this->konfigurasiRateLimiter();
    }

    /**
     * Batas percobaan untuk endpoint autentikasi (anti brute force).
     */
    private function konfigurasiRateLimiter(): void
    {
        // Login: 5 percobaan/menit per kombinasi username+IP, dan 20/menit per IP
        // agar penyerang tidak bisa memutar-mutar daftar username dari satu IP.
        RateLimiter::for('login', function (Request $request) {
            $username = Str::lower((string) $request->input('username'));

            return [
                Limit::perMinute(5)->by($username.'|'.$request->ip()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        // Registrasi: cegah pembuatan akun massal dari satu IP.
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    }
}
