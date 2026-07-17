<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginNotificationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class GoogleController extends Controller
{
    /**
     * Redirect ke halaman consent Google.
     */
    public function redirect(): SymfonyRedirect
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google: buat/temukan user lalu login.
     */
    public function callback(): RedirectResponse
    {
        try {
            // Di lokal, disable SSL verification (hanya untuk development!)
            if (config('app.env') === 'local') {
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $googleUser = Socialite::driver('google')->setHttpClient($client)->user();
            } else {
                $googleUser = Socialite::driver('google')->user();
            }
        } catch (\Throwable $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(40)),
                'role' => 'user',
            ]);
        }

        Auth::login($user, true);

        // Kirim notifikasi email login
        Mail::send(new LoginNotificationMail($user));

        // Akun Google belum punya nama pengguna — minta dilengkapi dulu, persis
        // seperti user yang mendaftar manual.
        if ($user->butuhLengkapiProfil()) {
            return redirect()->route('profil.lengkapi');
        }

        return redirect()->route('landing');
    }
}
