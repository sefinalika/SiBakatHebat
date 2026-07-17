<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'google_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Boleh melihat dashboard sebaran seluruh peserta — HANYA admin.
     * Guru cukup melihat anak didik yang ia daftarkan sendiri (privasi peserta).
     */
    public function canViewDashboard(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Akun yang profilnya belum lengkap — mis. akun Google baru yang belum punya
     * username. Data anak TIDAK dicek di sini: satu akun bisa punya banyak anak,
     * dan anak didaftarkan lewat halaman "Anak Didik".
     */
    public function butuhLengkapiProfil(): bool
    {
        return blank($this->username);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Super Admin',
            'guru' => 'Guru',
            default => 'Peserta',
        };
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'user_id');
    }
}
