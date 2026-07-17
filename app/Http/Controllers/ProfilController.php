<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Melengkapi profil akun — dipakai akun Google yang belum punya nama pengguna.
 * Data anak TIDAK diminta di sini (lihat AnakController).
 */
class ProfilController extends Controller
{
    public function lengkapi(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->butuhLengkapiProfil()) {
            return redirect()->route('landing');
        }

        return view('profil.lengkapi', [
            'usernameDefault' => $this->usernameDariNama($user->name),
        ]);
    }

    public function simpan(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'username' => [
                'required', 'string', 'min:3', 'max:50', 'regex:/^[\p{L} ]+$/u',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.regex' => 'Nama pengguna hanya boleh berisi huruf dan spasi (tanpa angka atau karakter lain).',
            'username.min' => 'Nama pengguna minimal 3 karakter.',
            'username.max' => 'Nama pengguna maksimal 50 karakter.',
            'username.unique' => 'Nama pengguna sudah dipakai, silakan pilih yang lain.',
        ]);

        $user->forceFill([
            'username' => $data['username'],
            'name' => $data['username'],
        ])->save();

        return redirect()->route('anak.index')
            ->with('status', 'Profil tersimpan. Sekarang daftarkan anak didik Anda.');
    }

    /**
     * Usulan nama pengguna dari nama akun Google: hanya huruf & spasi.
     */
    private function usernameDariNama(?string $nama): string
    {
        $bersih = trim(preg_replace('/\s+/u', ' ', preg_replace('/[^\p{L} ]/u', '', (string) $nama)));

        return mb_strlen($bersih) >= 3 ? mb_substr($bersih, 0, 50) : '';
    }
}
