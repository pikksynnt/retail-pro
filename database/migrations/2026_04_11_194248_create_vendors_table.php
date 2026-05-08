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
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pemilik UMKM
        $table->string('shop_name');
        $table->string('slug')->unique(); // buat URL lapak (misal: /toko/keripik-enak)
        $table->text('description')->nullable();
        $table->string('address')->nullable();
        $table->string('phone')->nullable();
        $table->string('logo')->nullable();
        $table->enum('status', ['pending', 'verified', 'active'])->default('pending');
        $table->timestamps();
    });
    }
};
