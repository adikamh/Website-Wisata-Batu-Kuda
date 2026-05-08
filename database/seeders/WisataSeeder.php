<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wisata;

class WisataSeeder extends Seeder
{
    public function run(): void
    {
        Wisata::updateOrCreate(
            ['nama_wisata' => 'Batu Kuda'],
            [
                'nama_wisata' => 'Batu Kuda',
                'deskripsi'   => 'Batu Kuda adalah kawasan wisata alam yang terletak di kawasan hutan Perhutani, '
                               . 'Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung. Namanya berasal dari sebuah '
                               . 'formasi batu besar yang konon menyerupai kuda yang sedang duduk — menjadi daya '
                               . 'tarik utama yang penuh misteri dan legenda. Berada di ketinggian sekitar 1.200 mdpl '
                               . 'di lereng Gunung Manglayang, kawasan ini menawarkan udara segar, hamparan pohon '
                               . 'pinus yang rindang, serta jalur hiking yang cocok untuk semua kalangan.',
                'lokasi'      => 'Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung, Jawa Barat. '
                               . 'Koordinat: -6.9037, 107.7471. Jarak ±25 km dari pusat Kota Bandung.',
                'gambar_url'  => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=900&q=80',
            ]
        );
    }
}
