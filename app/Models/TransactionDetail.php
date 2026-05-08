<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    // Supaya bisa simpan data lewat controller
    protected $fillable = [
        'transaction_id', 
        'product_id', 
        'qty', 
        'price', 
        'subtotal'
    ];

    /**
     * Relasi: Rincian ini milik sebuah transaksi
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi: Rincian ini merujuk ke sebuah produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}