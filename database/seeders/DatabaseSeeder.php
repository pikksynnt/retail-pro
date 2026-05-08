<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. BUAT AKUN SUPER ADMIN
        User::create([
            'name'     => 'Admin System',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'admin'
        ]);

        // 2. BUAT AKUN VENDOR CONTOH (MITRA UMKM)
        $vendorUser = User::create([
            'name'     => 'Budi Owner',
            'email'    => 'vendor@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'vendor'
        ]);

        // 3. BUAT PROFIL TOKO UNTUK VENDOR TERSEBUT
        // Ini penting supaya sistem kita nggak eror saat nyari $user->vendor->id
        Vendor::create([
            'user_id'   => $vendorUser->id,
            'shop_name' => 'Toko Sembako Budi',
            'address'   => 'Jl. Raya Merdeka No. 123',
            'phone'     => '08123456789',
            'status'    => 'active', // Langsung aktif biar bisa ngetes
        ]);

        // Info di console
        $this->command->info('Database Berhasil Diisi!');
        $this->command->info('Admin: admin@gmail.com | password');
        $this->command->info('Vendor: vendor@gmail.com | password');
    }
}