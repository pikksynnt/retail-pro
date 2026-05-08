<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'identity_number', // NIK KTP
        'description',
        'address',
        'logo',
        'identity_file',   // Foto KTP
        'status'           // pending, active, rejected
    ];

    /**
     * Otomatis memunculkan URL foto saat Model diubah jadi Array/JSON.
     */
    protected $appends = ['logo_url', 'ktp_url'];

    /**
     * Boot function: Otomatisasi saat data dibuat.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($vendor) {
            // Otomatis bikin slug unik jika kosong
            if (empty($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->shop_name) . '-' . Str::random(5);
            }
            
            // Set status default 'pending' jika belum ada status
            if (empty($vendor->status)) {
                $vendor->status = 'pending';
            }
        });
    }

    // =========================================================================
    // RELATIONS
    // =========================================================================

    /**
     * Relasi: Vendor dimiliki oleh satu User (Pemilik).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Satu Vendor memiliki banyak Produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    /**
     * Relasi: Satu Vendor memiliki banyak Pesanan (Multi-Vendor System).
     * Ini relasi baru buat sistem checkout yang kita buat tadi.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

    /**
     * Relasi: Satu Vendor memantau banyak Transaksi (Legacy Transaction).
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vendor_id');
    }

    /**
     * Relasi: Satu Vendor memiliki banyak Kategori Produk.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'vendor_id');
    }

    // =========================================================================
    // ACCESSORS & HELPERS
    // =========================================================================

    /**
     * Helper: Cek status vendor.
     */
    public function isActive() { return $this->status === 'active'; }
    public function isPending() { return $this->status === 'pending'; }
    public function isRejected() { return $this->status === 'rejected'; }

    /**
     * Accessor: Panggil Logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            $path = 'storage/logos/' . $this->logo;
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->shop_name) . '&background=random&color=fff';
    }

    /**
     * Accessor: Panggil Foto KTP URL
     */
    public function getKtpUrlAttribute()
    {
        if ($this->identity_file) {
            $path = 'uploads/identitas/' . $this->identity_file;
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }
        return 'https://placehold.co/600x400?text=KTP+Tidak+Tersedia';
    }
}