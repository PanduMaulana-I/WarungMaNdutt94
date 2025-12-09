<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Kolom yang bisa diisi
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahin role biar bisa bedain seller / buyer
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Casting otomatis
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke orders (setiap user bisa punya banyak pesanan)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Ambil inisial dari nama
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Cek apakah user adalah penjual
     */
    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    /**
     * Cek apakah user adalah pembeli
     */
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }
}
