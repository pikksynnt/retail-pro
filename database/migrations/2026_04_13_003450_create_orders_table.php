<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Relasi ke pembeli (users)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Relasi ke toko (vendors) - Ini kunci Multi-Vendor lu!
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
        
            $table->string('order_number')->unique(); // Contoh: INV-20260413-XYZ
            $table->decimal('total_price', 15, 2);
        
            // Status alur kerja (Fungsional No. 3 di daftar lu)
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
        
            $table->text('shipping_address')->nullable();
            $table->string('payment_status')->default('unpaid'); // Untuk integrasi Payment Gateway nanti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
