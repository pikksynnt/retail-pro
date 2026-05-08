<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'vendor_id', 
        'order_number', 
        'total_price', 
        'status', 
        'shipping_address', 
        'payment_status'
    ];

    // Relasi: Satu order punya banyak item barang
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order ini milik Vendor (Toko) tertentu
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi: Order ini milik User (Pembeli) tertentu
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}