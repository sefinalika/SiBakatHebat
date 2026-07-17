<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginNotificationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Registrasi akun pengisi tes (guru atau wali murid).
     * Data anak TIDAK diminta di sini — satu akun bisa mendaftarkan banyak anak
     * lewat halaman "Anak Didik".
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L} ]+$/u', 'unique:users,username'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.required' => 'Nama Anda wajib diisi.',
            'username.regex' => 'Nama hanya boleh berisi huruf dan spasi (tanpa angka atau karakter lain).',
            'username.min' => 'Nama minimal 3 karakter.',
            'username.max' => 'Nama maksimal 50 karakter.',
            'username.unique' => 'Nama itu sudah dipakai untuk login, silakan pilih yang lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan masuk dengan akun tersebut.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name' => $data['username'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'], // di-hash via cast 'hashed'
            'role' => 'user',
        ]);

        // Tidak auto-login: arahkan ke halaman login agar user masuk dengan akunnya.
        return redirect()->route('login')
            ->with('status', 'Akun berhasil dibuat. Silakan masuk, lalu daftarkan anak didik Anda.');
    }

    /**
     * Login menggunakan username (bukan email).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Nama pengguna atau password salah.']);
        }

        $request->session()->regenerate();

        // Kirim notifikasi email login
        Mail::send(new LoginNotificationMail(Auth::user()));

        return redirect()->intended(route('landing'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
