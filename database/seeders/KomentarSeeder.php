<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Komentar;

class KomentarSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user dengan id 1, 2, 3
        // dan gallery dengan id 1, 2, 3
        
        $komentars = [
            [
                'gallery_id' => 1,
                'user_id' => 1,
                'isi_komentar' => 'Wah sunsetnya keren banget! Pengen kesini lagi.',
            ],
            [
                'gallery_id' => 1,
                'user_id' => 2,
                'isi_komentar' => 'Foto yang indah! Terima kasih sudah berbagi.',
            ],
            [
                'gallery_id' => 2,
                'user_id' => 1,
                'isi_komentar' => 'Spot selfienya unik banget. Wajib foto disini!',
            ],
            [
                'gallery_id' => 2,
                'user_id' => 3,
                'isi_komentar' => 'Formasi batunya mirip kuda ya? Keren!',
            ],
            [
                'gallery_id' => 3,
                'user_id' => 2,
                'isi_komentar' => 'Area campingnya bersih dan nyaman. Recommended!',
            ],
            [
                'gallery_id' => 4,
                'user_id' => 1,
                'isi_komentar' => 'Cocok banget buat liburan keluarga.',
            ],
            [
                'gallery_id' => 5,
                'user_id' => 3,
                'isi_komentar' => 'Pemandangan paginya bikin adem hati.',
            ],
            [
                'gallery_id' => 6,
                'user_id' => 2,
                'isi_komentar' => 'Makanannya enak-enak! Cobain sate kambingnya.',
            ],
            [
                'gallery_id' => 3,
                'user_id' => 1,
                'isi_komentar' => 'Next mau camping lagi deh disini.',
            ],
            [
                'gallery_id' => 1,
                'user_id' => 3,
                'isi_komentar' => 'Jam berapa biasanya sunset terbaik?',
            ],
        ];

        foreach ($komentars as $komentar) {
            Komentar::create($komentar);
        }
    }
}