<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfilLengkap
{
    /**
     * Akun yang belum punya nama pengguna (mis. akun Google baru) dikunci di
     * halaman lengkapi profil — sama seperti akun yang mendaftar manual, yang
     * namanya sudah terisi sejak registrasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->butuhLengkapiProfil()) {
            return redirect()->route('profil.lengkapi')
                ->with('status', 'Lengkapi profil Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
