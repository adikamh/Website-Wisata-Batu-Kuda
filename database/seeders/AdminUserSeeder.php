<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'batukuda@gmail.com',
            'password' => Hash::make('batukuda123'),
            'role' => 'admin',
            'Phone' => '081234567890',
            'Address' => 'Jl. Contoh No. 123, Kota Bandung',
            'is_verified' => true,
        ]);

        // User Joko Widodo
        User::create([
            'name' => 'Joko Widodo',
            'username' => 'joko',
            'email' => 'joko@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'Phone' => '081234567893',
            'Address' => 'Jl. Diponegoro No. 20',
            'is_verified' => false, // belum verifikasi
        ]);

        // User Biasa (contoh lain)
        User::create([
            'name' => 'User Biasa',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'Phone' => '081234567890',
            'Address' => 'Jl. Contoh No. 123, Kota Wisata',
            'is_verified' => true,
        ]);
    }
}