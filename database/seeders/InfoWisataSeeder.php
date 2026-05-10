<?php

namespace Database\Seeders;

use App\Models\InfoWisata;
use Illuminate\Database\Seeder;

class InfoWisataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'judul'    => 'Harga Tiket & Biaya Masuk',
                'kategori' => 'Harga & Tiket',
                'icon'     => '🎫',
                'deskripsi'=> 'Batu Kuda menawarkan harga tiket yang sangat terjangkau sehingga cocok untuk semua kalangan, mulai dari keluarga muda hingga rombongan wisatawan.',
                'urutan'   => 1,
                'poin'     => [
                    ['judul' => 'Tiket Masuk Dewasa', 'isi' => 'Rp 10.000 per orang untuk pengunjung usia 12 tahun ke atas.'],
                    ['judul' => 'Tiket Masuk Anak-anak', 'isi' => 'Rp 5.000 per anak untuk usia di bawah 12 tahun.'],
                    ['judul' => 'Parkir Kendaraan', 'isi' => 'Motor: Rp 5.000 · Mobil: Rp 10.000 · Bus/Minibus: Rp 20.000.'],
                    ['judul' => 'Tiket Berkuda', 'isi' => 'Tersedia wahana berkuda seharga Rp 20.000 – 30.000 per putaran.'],
                ],
                'gambar'   => [],
            ],
            [
                'judul'    => 'Jam Operasional',
                'kategori' => 'Informasi Umum',
                'icon'     => '🕐',
                'deskripsi'=> 'Kawasan Batu Kuda buka setiap hari termasuk hari libur nasional. Disarankan datang pagi hari untuk mendapatkan udara paling segar dan pemandangan terbaik.',
                'urutan'   => 2,
                'poin'     => [
                    ['judul' => 'Hari Biasa (Senin–Jumat)', 'isi' => 'Buka pukul 06.00 – 17.00 WIB.'],
                    ['judul' => 'Akhir Pekan & Libur', 'isi' => 'Buka pukul 05.30 – 17.30 WIB — lebih awal untuk sunrise.'],
                    ['judul' => 'Waktu Terbaik', 'isi' => 'Pukul 05.30 – 08.00 untuk sunrise dan udara pagi yang menyegarkan.'],
                ],
                'gambar'   => [],
            ],
            [
                'judul'    => 'Cara Menuju Batu Kuda',
                'kategori' => 'Transportasi',
                'icon'     => '🚗',
                'deskripsi'=> 'Batu Kuda berjarak sekitar 25 km dari pusat Kota Bandung dan dapat dicapai dengan berbagai moda transportasi. Kondisi jalan menanjak, pastikan kendaraan dalam kondisi prima.',
                'urutan'   => 3,
                'poin'     => [
                    ['judul' => 'Kendaraan Pribadi', 'isi' => 'Dari Bandung → Cicaheum → Jl. AH Nasution → Jl. Arcamanik → Desa Cikadut. Estimasi 45–60 menit.'],
                    ['judul' => 'Ojek Online', 'isi' => 'Pesan Gojek/Grab hingga area parkir Batu Kuda. Tarif sekitar Rp 25.000–40.000 dari Kota Bandung.'],
                    ['judul' => 'Angkutan Umum', 'isi' => 'Naik angkot jurusan Cicaheum, lanjut ojek lokal menuju Batu Kuda. Tarif ojek sekitar Rp 15.000.'],
                    ['judul' => 'Koordinat GPS', 'isi' => '-6.8567° LS, 107.7178° BT — bisa langsung dicari di Google Maps dengan kata kunci "Batu Kuda Bandung".'],
                ],
                'gambar'   => [],
            ],
            [
                'judul'    => 'Fasilitas Tersedia',
                'kategori' => 'Fasilitas',
                'icon'     => '🏕️',
                'deskripsi'=> 'Kawasan Batu Kuda dilengkapi fasilitas yang memadai untuk kenyamanan pengunjung selama berada di kawasan wisata.',
                'urutan'   => 4,
                'poin'     => [
                    ['judul' => 'Area Parkir Luas', 'isi' => 'Tersedia parkir untuk motor, mobil, dan bus rombongan.'],
                    ['judul' => 'Toilet & Mushola', 'isi' => 'Fasilitas sanitasi dan mushola tersedia di area parkir utama.'],
                    ['judul' => 'Warung & Pedagang', 'isi' => 'Tersedia warung makan, minuman hangat, dan camilan di sekitar area wisata.'],
                    ['judul' => 'Gazebo & Area Piknik', 'isi' => 'Tersedia gazebo dan area duduk di bawah pohon pinus untuk piknik keluarga.'],
                    ['judul' => 'Spot Foto', 'isi' => 'Beberapa spot foto instagramable tersedia termasuk di dekat Batu Kuda utama.'],
                ],
                'gambar'   => [],
            ],
            [
                'judul'    => 'Tips & Saran Berkunjung',
                'kategori' => 'Tips & Saran',
                'icon'     => '💡',
                'deskripsi'=> 'Ikuti tips berikut agar kunjungan Anda ke Batu Kuda menjadi pengalaman yang aman, nyaman, dan tak terlupakan.',
                'urutan'   => 5,
                'poin'     => [
                    ['judul' => 'Pakai Alas Kaki yang Tepat', 'isi' => 'Gunakan sepatu gunung atau sneakers dengan grip kuat karena jalur berbatu dan bisa licin saat hujan.'],
                    ['judul' => 'Bawa Jaket atau Sweater', 'isi' => 'Suhu pagi hari bisa mencapai 14–18°C. Jaket ringan atau sweater sangat dianjurkan.'],
                    ['judul' => 'Bawa Bekal dari Rumah', 'isi' => 'Pilihan makanan di area terbatas. Siapkan bekal, air minum yang cukup, dan camilan energi.'],
                    ['judul' => 'Jaga Kebersihan Alam', 'isi' => 'Bawa kantong plastik untuk sampah sendiri. Jangan tinggalkan sampah di kawasan hutan.'],
                    ['judul' => 'Hindari Musim Hujan', 'isi' => 'Jalur trekking lebih licin dan berbahaya saat musim hujan (November–Maret). Cek prakiraan cuaca sebelum berangkat.'],
                ],
                'gambar'   => [],
            ],
        ];

        foreach ($data as $item) {
            InfoWisata::updateOrCreate(
                ['judul' => $item['judul']],
                $item
            );
        }
    }
}