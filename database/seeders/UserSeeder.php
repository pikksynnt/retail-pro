<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat Akun Super Admin
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Ganti sesuai kemauan lu
            'role'     => 'admin', // Pastikan role-nya admin
        ]);

        // Opsional: Buat satu contoh vendor buat ngetes
        // User::create([ ... ]);
    }
}