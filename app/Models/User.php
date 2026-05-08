<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'vendor', atau 'customer'
    ];

    /**
     * The attributes that should be hidden for serialization.
     * * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // =========================================================================
    // RELATIONS
    // =========================================================================

    /**
     * Relasi: Satu User (dengan role vendor) memiliki satu profil Vendor (UMKM).
     * Digunakan untuk mengidentifikasi kepemilikan produk dan transaksi POS.
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    // =========================================================================
    // ROLE HELPERS (Standard Industry Logic)
    // =========================================================================

    /**
     * Cek apakah user adalah Administrator Sistem.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Mitra UMKM (Vendor).
     */
    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    /**
     * Cek apakah user adalah Pembeli Umum (Customer).
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}