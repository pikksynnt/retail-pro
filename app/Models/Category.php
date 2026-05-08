<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     * vendor_id ditambahkan agar kategori bersifat private per vendor.
     */
    protected $fillable = [
        'vendor_id', 
        'name', 
        'slug'
    ];

    /**
     * Boot function: Otomatis bikin slug pas kategori dibuat.
     * Jadi kalau input "Snack Ringan", slug-nya jadi "snack-ringan-123"
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name) . '-' . Str::random(5);
            }
        });
    }

    // =========================================================================
    // RELATIONS
    // =========================================================================

    /**
     * Relasi: Kategori dimiliki oleh satu Vendor.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Relasi: Satu kategori punya banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}