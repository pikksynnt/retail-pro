<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     */
    protected $fillable = [
        'vendor_id',
        'barcode', 
        'name', 
        'description', // Deskripsi Produk
        'image',       // Nama file gambar
        'category_id', 
        'stock', 
        'min_stock', 
        'price_eceran', 
        'unit'
    ];

    /**
     * Otomatis memunculkan image_url & average_rating saat Model diubah jadi Array/JSON.
     */
    protected $appends = ['image_url', 'avg_rating'];

    // =========================================================================
    // RELATIONS (RELASI DATABASE)
    // =========================================================================

    /**
     * Relasi: Produk dimiliki oleh satu Vendor (UMKM).
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Relasi: Produk masuk dalam satu Kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi: Satu produk bisa punya banyak riwayat harga (Jika ada).
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    /**
     * Relasi: Satu produk bisa punya banyak ulasan/rating (Fitur No. 6).
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * Relasi: Produk bisa muncul di banyak detail pesanan.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // =========================================================================
    // ACCESSORS & HELPERS (LOGIKA CERDAS)
    // =========================================================================

    /**
     * Accessor: Menghitung rata-rata rating bintang secara otomatis.
     */
    public function getAvgRatingAttribute()
    {
        // Ambil rata-rata rating, default 0 jika belum ada review
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    /**
     * Accessor Image URL.
     * Memastikan gambar yang ditampilkan valid.
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return $this->defaultPlaceholder();
        }

        // Cek file di storage/app/public/products/
        $path = 'storage/products/' . $this->image;
        if (!file_exists(public_path($path))) {
            return $this->defaultPlaceholder();
        }

        return url($path);
    }

    /**
     * Placeholder jika gambar tidak ditemukan.
     */
    private function defaultPlaceholder()
    {
        return 'https://images.unsplash.com/photo-1512428559083-5d04d33a6e6a?q=80&w=200&auto=format&fit=crop';
    }

    /**
     * Helper: Cek apakah stok sedang kritis.
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Helper: Format harga eceran ke Rupiah.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_eceran, 0, ',', '.');
    }
}