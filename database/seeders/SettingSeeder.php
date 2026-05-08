<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Setting::create([
        'shop_name' => 'Retail Pro - Toko Kelontong',
        'shop_address' => 'Jl. Informatika No. 10, Jakarta',
        'shop_phone' => '08123456789'
        ]);
    }
}
