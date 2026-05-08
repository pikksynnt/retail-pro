<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'member_code',
        'name',
        'phone',
        'discount_percent'
    ];

    // Relasi balik ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}