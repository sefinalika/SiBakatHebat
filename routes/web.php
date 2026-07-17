<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\ObservasiController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Setup DB lewat browser (untuk hosting tanpa command line, mis. InfinityFree).
// Aktif hanya jika APP_SETUP_TOKEN diisi & cocok. Kunjungi sekali, lalu hapus tokennya.
// Token minimal 32 karakter + throttle ketat supaya tidak bisa ditebak lewat brute force.
Route::get('/deploy/setup/{token}', function (string $token) {
    $expected = (string) config('app.setup_token');

    abort_unless(mb_strlen($expected) >= 32 && hash_equals($expected, $token), 404);

    Log::warning('Route setup deploy dijalankan.', ['ip' => request()->ip()]);

    Artisan::call('migrate', ['--force' => true]);
    $out = Artisan::output();
    Artisan::call('db:seed', ['--force' => true]);
    $out .= Artisan::output();

    return response('<pre style="font-family:monospace;padding:16px">'
        .e($out)."\n\n=== SELESAI ===\nDatabase siap. Untuk keamanan, hapus APP_SETUP_TOKEN dari .env.</pre>");
})->middleware('throttle:5,60')->name('deploy.setup');

// Root: belum login -> langsung ke form login; sudah login -> beranda.
// Akun yang profilnya belum lengkap (mis. akun Google baru) dibelokkan ke /profil/lengkapi.
Route::get('/', function () {
    return auth()->check()
        ? view('landing')
        : redirect()->route('login');
})->middleware('profil.lengkap')->name('landing');

// --- Autentikasi ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');

    // Lupa password (login pakai username, pemulihan pakai email akun).
    Route::get('/lupa-password', [PasswordResetController::class, 'formLupa'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'kirimTautan'])
        ->middleware('throttle:3,10')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'formReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:6,10')->name('password.update');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Lengkapi profil akun (butuh login, TIDAK boleh dikunci middleware profil) ---
Route::middleware('auth')->group(function () {
    Route::get('/profil/lengkapi', [ProfilController::class, 'lengkapi'])->name('profil.lengkapi');
    Route::post('/profil/lengkapi', [ProfilController::class, 'simpan'])->name('profil.simpan');
});

// --- Anak didik & alur tes (butuh login + profil lengkap) ---
Route::middleware(['auth', 'profil.lengkap'])->group(function () {
    // Daftar anak didik: satu akun guru/wali boleh punya banyak anak.
    Route::get('/anak', [AnakController::class, 'index'])->name('anak.index');
    Route::get('/anak/tambah', [AnakController::class, 'create'])->name('anak.create');
    Route::post('/anak', [AnakController::class, 'store'])->name('anak.store');
    Route::get('/anak/{anak}/ubah', [AnakController::class, 'edit'])->name('anak.edit');
    Route::put('/anak/{anak}', [AnakController::class, 'update'])->name('anak.update');
    Route::delete('/anak/{anak}', [AnakController::class, 'destroy'])->name('anak.destroy');
    Route::post('/anak/{anak}/mulai', [AnakController::class, 'mulaiTes'])->name('anak.mulai');

    Route::get('/soal', [ObservasiController::class, 'showSoal'])->name('soal.show');
    Route::post('/soal', [ObservasiController::class, 'storeSoal'])->name('soal.store');

    Route::get('/riwayat', [HasilController::class, 'riwayat'])->name('riwayat');

    Route::get('/hasil/{observasi}', [HasilController::class, 'tampil'])->name('hasil.show');
    Route::get('/hasil/{observasi}/pdf', [HasilController::class, 'exportPdf'])->name('hasil.pdf');
    // Dibatasi agar tidak dipakai mengirim email beruntun ke alamat orang lain.
    Route::post('/hasil/{observasi}/kirim-email', [HasilController::class, 'kirimEmail'])
        ->middleware('throttle:5,10')->name('hasil.kirim-email');
    Route::get('/api/hasil/{observasi}', [HasilController::class, 'apiHasil'])->name('api.hasil.show');
});

// --- Dashboard (admin & guru) ---
Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
    Route::get('/api/admin/sebaran', [AdminController::class, 'apiSebaran'])->name('api.admin.sebaran');
});
