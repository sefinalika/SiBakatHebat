<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

/**
 * Lupa password: kirim tautan reset ke email, lalu setel password baru.
 * Login memakai nama pengguna, tapi pemulihan memakai email akun.
 */
class PasswordResetController extends Controller
{
    public function formLupa(): View
    {
        return view('auth.lupa-password');
    }

    /**
     * Kirim tautan reset ke email. Jawaban selalu sama, baik email terdaftar
     * maupun tidak — supaya orang tidak bisa menebak email mana yang terdaftar.
     */
    public function kirimTautan(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => ['required', 'email']],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
            ]
        );

        Password::sendResetLink($request->only('email'));

        return back()->with('status',
            'Jika email tersebut terdaftar, kami sudah mengirim tautan untuk menyetel ulang password. '
            .'Silakan cek kotak masuk (dan folder spam).'
        );
    }

    public function formReset(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password, // di-hash via cast 'hashed'
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PasswordReset) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Tautan reset tidak berlaku atau sudah kedaluwarsa. Silakan minta tautan baru.']);
        }

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan masuk dengan password baru Anda.');
    }
}
