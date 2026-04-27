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
            'email' => 'admin@wisatabatukuda.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'Phone' => '081234567890',
            'Address' => 'Jl. Contoh No. 123, Kota Bandung',
            'is_verified' => true,
        ]);
        
        // User biasa (contoh)
        User::create([
            'name' => 'User Biasa',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'Phone' => '081234567890',
            'Address' => 'Jl. Contoh No. 123, Kota Wisata',
            'role' => 'user',
            'is_verified' => true,
        ]);
    }
}