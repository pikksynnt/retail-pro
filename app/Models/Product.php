<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'barcode',
        'name',
        'description',
        'image',
        'external_image_url',
        'category_id',
        'stock',
        'min_stock',
        'price_eceran',
        'unit',
    ];

    protected $appends = ['image_url', 'avg_rating'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function getAvgRatingAttribute()
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->external_image_url)) {
            return $this->external_image_url;
        }

        if (!env('VERCEL') && !empty($this->image)) {
            $path = 'storage/products/' . $this->image;

            if (file_exists(public_path($path))) {
                return url($path);
            }
        }

        return $this->defaultPlaceholder();
    }

    private function defaultPlaceholder()
    {
        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=600&auto=format&fit=crop';
    }

    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_eceran, 0, ',', '.');
    }
}