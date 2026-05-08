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
    Schema::table('transactions', function (Blueprint $table) {
        // Tambahkan vendor_id dan user_id setelah invoice_number
        $table->foreignId('vendor_id')->nullable()->after('id')->constrained();
        $table->foreignId('user_id')->nullable()->after('invoice_number')->constrained();
        $table->foreignId('member_id')->nullable()->after('user_id')->constrained();
    });
}

    public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        // 1. Lepas ikatannya dulu (Foreign Key)
        $table->dropForeign(['vendor_id']);
        $table->dropForeign(['user_id']);
        $table->dropForeign(['member_id']);

        // 2. Baru hapus kolomnya
        $table->dropColumn(['vendor_id', 'user_id', 'member_id']);
    });
}
};
