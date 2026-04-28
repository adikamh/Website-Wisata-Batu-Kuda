<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Support\Facades\Auth;

class WisataController
{
    /**
     * Dashboard user — menampilkan 1 wisata utama (Batu Kuda)
     */
    public function dashboard()
    {
        // Ambil data Batu Kuda dari database (id=1 atau cari by nama)
        $wisata = Wisata::where('nama_wisata', 'like', '%Batu Kuda%')->first();

        // Fallback data statis jika DB belum terisi
        if (!$wisata) {
            $wisata = (object) [
                'nama_wisata' => 'Batu Kuda',
                'deskripsi'   => 'Batu Kuda adalah kawasan wisata alam di lereng Gunung Manglayang, '
                               . 'Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung. Namanya berasal '
                               . 'dari formasi batu besar yang menyerupai kuda yang sedang duduk.',
                'lokasi'      => 'Desa Cikadut, Kec. Cimenyan, Kabupaten Bandung, Jawa Barat',
                'gambar_url'  => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=900&q=80',
            ];
        }

        return view('dashboard', compact('wisata'));
    }

    public function tiket()
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk mengakses tiket.');
        }

        return view('tiket-placeholder');
    }
}
