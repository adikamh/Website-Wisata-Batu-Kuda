<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $fotos = [
            [
                'judul_foto' => 'Sunset di Batu Kuda',
                'deskripsi' => 'Pemandangan matahari terbenam yang spektakuler dari puncak Batu Kuda. Warna jingga keemasan menghiasi langit.',
                'gambar_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900&q=80',
            ],
            [
                'judul_foto' => 'Spot Selfie Favorit',
                'deskripsi' => 'Spot selfie dengan latar belakang formasi batu unik yang menjadi ikon Wisata Batu Kuda.',
                'gambar_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=900&q=80',
            ],
            [
                'judul_foto' => 'Area Camping',
                'deskripsi' => 'Area camping yang nyaman dengan pemandangan alam yang indah. Cocok untuk family gathering.',
                'gambar_url' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=900&q=80',
            ],
            [
                'judul_foto' => 'Wisata Keluarga',
                'deskripsi' => 'Suasana wisata yang ramah keluarga. Banyak wahana permainan untuk anak-anak.',
                'gambar_url' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=900&q=80',
            ],
            [
                'judul_foto' => 'Pemandangan Pagi',
                'deskripsi' => 'Kabut tipis menyelimuti kawasan Batu Kuda di pagi hari. Sangat Instagramable!',
                'gambar_url' => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=900&q=80',
            ],
            [
                'judul_foto' => 'Warung Makan Khas',
                'deskripsi' => 'Nikmati kuliner khas daerah sambil menikmati pemandangan alam.',
                'gambar_url' => 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=900&q=80',
            ],
        ];

        foreach ($fotos as $foto) {
            Gallery::updateOrCreate(
                ['judul_foto' => $foto['judul_foto']],
                $foto
            );
        }
    }
}
