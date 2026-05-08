<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Daftarkan semua kolom yang boleh diisi secara massal.
     * PENTING: vendor_id wajib ada agar data antar UMKM tidak tercampur.
     */
    protected $fillable = [
        'vendor_id',      // Wajib ada untuk sistem Mitra UMKM
        'invoice_number', 
        'user_id',        // Kasir
        'member_id',      // Pelanggan
        'total_price', 
        'cash', 
        'change'
    ];

    /**
     * Relasi ke Vendor (Toko/Mitra UMKM)
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relasi ke User (Kasir yang melayani)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Member (Pelanggan)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relasi ke Detail Transaksi (Satu transaksi punya banyak barang)
     * Menggunakan hasMany karena satu invoice berisi banyak item belanjaan
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Boot function untuk logika otomatis (Opsional)
     * Bisa digunakan untuk generate invoice number otomatis jika ingin lebih rapi
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->invoice_number)) {
                $model->invoice_number = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }
}