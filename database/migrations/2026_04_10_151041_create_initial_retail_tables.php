<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kategori
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Tabel Produk (Master Data)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5); 
            $table->decimal('price_eceran', 15, 2)->default(0); // KOLOM INI YANG TADI HILANG
            $table->string('unit')->default('pcs'); 
            $table->softDeletes(); // AGAR HAPUS BARANG AMAN (Soft Delete)
            $table->timestamps();
        });

        // 3. Tabel Multi-Harga (Relasi ke tabel harga lain jika butuh)
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('price_type'); // Eceran, Grosir, Member
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};