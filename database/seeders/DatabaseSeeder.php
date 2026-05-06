<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            WisataSeeder::class,
            TiketKategoriSeeder::class,     // 1. Users dulu
            GallerySeeder::class,        // 2. Gallery
            KomentarSeeder::class,       // 3. Komentar (butuh user & gallery)
            LikeFotoSeeder::class,
        ]);
    }
}
