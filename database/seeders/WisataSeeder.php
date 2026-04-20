<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wisata;  // ← Tambahkan ini!

class WisataSeeder extends Seeder
{
    public function run()
    {
        Wisata::create([
            'nama_wisata' => 'Wisata Batu Kuda',
            'deskripsi' => 'Destinasi wisata alam dengan pemandangan pegunungan yang indah, cocok untuk camping dan bersantai.',
            'lokasi' => 'Desa Wisata, Kecamatan Lembang, Bandung Barat',
            'gambar_url' => '/images/wisata/batu-kuda.jpg',
        ]);
    }
}