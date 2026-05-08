<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Masukkan kolom yang bisa diisi
    protected $fillable = ['shop_name', 'shop_address', 'shop_phone'];
}