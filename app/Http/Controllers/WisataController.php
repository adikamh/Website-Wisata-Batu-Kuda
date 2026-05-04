<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WisataController
{
    /**
     * Dashboard user — menampilkan 1 wisata utama (Batu Kuda)
     */
    public function dashboard()
    {
        try {
            // Ambil data Batu Kuda dari database (id=1 atau cari by nama)
            $wisata = Wisata::where('nama_wisata', 'like', '%Batu Kuda%',true)->first();
        } catch (QueryException) {
            $wisata = null;
        }

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

        return view('layout.dashboard', compact('wisata'));
    }

    public function tiket()
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk mengakses tiket.');
        }

        $ticketPackages = $this->ticketPackages();
        $paymentOptions = $this->paymentOptions();
        $recentTicket = session('recentTicket');

        return view('layout.tiket', compact('ticketPackages', 'paymentOptions', 'recentTicket'));
    }

    public function storeTiket(Request $request)
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk memesan tiket.');
        }

        $ticketPackages = $this->ticketPackages();
        $paymentOptions = $this->paymentOptions();

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'package_type' => ['required', Rule::in(array_keys($ticketPackages))],
            'visitor_count' => ['required', 'integer', 'min:1', 'max:20'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'camping_end_date' => ['nullable', 'required_if:package_type,camping', 'date', 'after_or_equal:visit_date'],
            'payment_category' => ['required', Rule::in(array_keys($paymentOptions))],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'package_type.required' => 'Paket wisata wajib dipilih.',
            'visitor_count.required' => 'Jumlah orang wajib diisi.',
            'visitor_count.min' => 'Jumlah orang minimal 1.',
            'visitor_count.max' => 'Jumlah orang maksimal 20.',
            'visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'camping_end_date.required_if' => 'Tanggal selesai camping wajib diisi untuk paket camping.',
            'camping_end_date.after_or_equal' => 'Tanggal selesai camping tidak boleh sebelum tanggal mulai.',
            'payment_category.required' => 'Kategori pembayaran wajib dipilih.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        if (! array_key_exists($validated['payment_method'], $paymentOptions[$validated['payment_category']])) {
            return back()
                ->withInput()
                ->withErrors(['payment_method' => 'Metode pembayaran tidak sesuai dengan kategori yang dipilih.']);
        }

        $startDate = Carbon::parse($validated['visit_date']);
        $endDate = $validated['package_type'] === 'camping'
            ? Carbon::parse($validated['camping_end_date'])
            : $startDate;
        $totalDays = (int) $startDate->diffInDays($endDate) + 1;
        $visitorCount = (int) $validated['visitor_count'];
        $package = $ticketPackages[$validated['package_type']];
        $totalBayar = $package['price'] * $visitorCount * $totalDays;
        $paymentMethodLabel = $paymentOptions[$validated['payment_category']][$validated['payment_method']];

        return redirect()
            ->route('tiket')
            ->with('status', 'Pesanan tiket berhasil dibuat.')
            ->with('recentTicket', [
                'ticket_code' => 'BK-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                'transaction_id' => random_int(100000, 999999),
                'package_name' => $package['name'],
                'visitor_count' => $visitorCount,
                'payment_method_label' => $paymentMethodLabel,
                'total_bayar' => $totalBayar,
            ]);
    }

    private function ticketPackages(): array
    {
        return [
            'visit' => [
                'name' => 'Kunjungan Harian',
                'description' => 'Tiket masuk reguler untuk menikmati area wisata Batu Kuda.',
                'price' => 10000,
                'features' => [
                    'Akses area wisata utama',
                    'Berlaku untuk satu hari kunjungan',
                    'Cocok untuk keluarga dan rombongan kecil',
                ],
            ],
            'camping' => [
                'name' => 'Camping',
                'description' => 'Paket bermalam untuk pengunjung yang ingin camping di kawasan Batu Kuda.',
                'price' => 25000,
                'features' => [
                    'Akses area camping',
                    'Perhitungan harga per orang per hari',
                    'Cocok untuk komunitas dan petualang',
                ],
            ],
        ];
    }

    private function paymentOptions(): array
    {
        return [
            'bank' => [
                'bca' => 'Bank BCA',
                'bri' => 'Bank BRI',
                'mandiri' => 'Bank Mandiri',
            ],
            'ewallet' => [
                'gopay' => 'GoPay',
                'ovo' => 'OVO',
                'dana' => 'DANA',
            ],
            'qris' => [
                'qris' => 'QRIS',
            ],
        ];
    }
}
