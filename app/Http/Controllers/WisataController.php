<?php

namespace App\Http\Controllers;

use App\Models\ETicket;
use App\Models\PaketWisata;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Wisata;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        return view('dashboard', compact('wisata'));
    }

    public function tiket()
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk mengakses tiket.');
        }

        return view('layout.tiket', [
            'ticketPackages' => $this->getTicketPackages(),
            'paymentOptions' => $this->getPaymentOptions(),
            'recentTicket' => session('ticket_booking'),
        ]);
    }

    public function storeTiket(Request $request)
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk memesan tiket.');
        }

        $user = $request->user();
        $paymentOptions = $this->getPaymentOptions();

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'package_type' => ['required', 'in:visit,camping'],
            'visitor_count' => ['required', 'integer', 'min:1', 'max:20'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'camping_end_date' => ['nullable', 'date', 'after_or_equal:visit_date'],
            'payment_category' => ['required', 'in:bank,ewallet,qris'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'package_type.required' => 'Silakan pilih paket kunjungan.',
            'visitor_count.required' => 'Jumlah orang wajib diisi.',
            'visitor_count.min' => 'Jumlah orang minimal 1.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
            'visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'camping_end_date.after_or_equal' => 'Tanggal selesai camping harus setelah atau sama dengan tanggal mulai.',
            'payment_category.required' => 'Pilih kategori pembayaran terlebih dahulu.',
            'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
        ]);

        $selectedCategoryOptions = $paymentOptions[$validated['payment_category']] ?? [];

        if (! array_key_exists($validated['payment_method'], $selectedCategoryOptions)) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_method' => 'Metode pembayaran tidak sesuai dengan kategori yang dipilih.',
                ]);
        }

        $ticketPackages = $this->getTicketPackages();
        $selectedPackage = $ticketPackages[$validated['package_type']];
        $isCamping = $validated['package_type'] === 'camping';
        $visitDate = $validated['visit_date'];
        $endDate = $isCamping ? ($validated['camping_end_date'] ?: $validated['visit_date']) : null;
        $totalDays = $isCamping
            ? Carbon::parse($visitDate)->diffInDays(Carbon::parse($endDate)) + 1
            : 1;
        $subtotal = $selectedPackage['price'] * $validated['visitor_count'] * $totalDays;

        $booking = DB::transaction(function () use ($user, $validated, $selectedPackage, $selectedCategoryOptions, $subtotal, $visitDate, $endDate, $totalDays) {
            $user->forceFill([
                'Phone' => $validated['phone'],
            ])->save();

            $paket = PaketWisata::firstOrCreate(
                ['nama_paket' => $selectedPackage['name']],
                [
                    'deskripsi_paket' => $selectedPackage['description'],
                    'harga_paket' => $selectedPackage['price'],
                    'is_active' => true,
                ]
            );

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_bayar' => $subtotal,
                'status_pembayaran' => 'pending',
                'payment_method' => $validated['payment_category'] . ':' . $validated['payment_method'],
                'snap_token_midtrans' => null,
            ]);

            $detail = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'tiket_kategori_id' => null,
                'paket_id' => $paket->id,
                'quantity' => $validated['visitor_count'],
                'subtotal' => $subtotal,
                'package_type' => $validated['package_type'],
                'start_date' => $visitDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'extra_days' => max(0, $totalDays - 1),
                'extra_days_charge' => 0,
                'tax_amount' => 0,
                'grand_total' => $subtotal,
            ]);

            $ticketCode = 'BKD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            ETicket::create([
                'transaction_detail_id' => $detail->id,
                'ticket_code' => $ticketCode,
                'qr_code_hash' => hash('sha256', $ticketCode . '|' . $transaction->id . '|' . $user->email),
                'watermark_path' => null,
                'is_used' => false,
                'validated_at' => null,
            ]);

            return [
                'ticket_code' => $ticketCode,
                'transaction_id' => $transaction->id,
                'package_name' => $selectedPackage['name'],
                'visitor_count' => (int) $validated['visitor_count'],
                'visit_date' => $visitDate,
                'camping_end_date' => $endDate,
                'total_days' => $totalDays,
                'payment_method_label' => $selectedCategoryOptions[$validated['payment_method']],
                'total_bayar' => $subtotal,
                'notes' => $validated['notes'] ?? null,
            ];
        });

        return redirect()
            ->route('tiket')
            ->with('status', 'Pemesanan tiket berhasil dibuat. Silakan lanjutkan pembayaran.')
            ->with('ticket_booking', $booking);
    }

    private function getTicketPackages(): array
    {
        $defaults = [
            'visit' => [
                'name' => 'Kunjungan Biasa',
                'description' => 'Akses masuk kawasan wisata Batu Kuda untuk rekreasi harian.',
                'price' => 15000,
                'features' => [
                    'Akses area wisata Batu Kuda',
                    'Cocok untuk keluarga dan rombongan kecil',
                    'Berlaku untuk satu hari kunjungan',
                ],
            ],
            'camping' => [
                'name' => 'Camping',
                'description' => 'Paket menginap di area camping ground Batu Kuda.',
                'price' => 35000,
                'features' => [
                    'Akses camping ground dan area wisata',
                    'Cocok untuk petualangan malam dan sunrise',
                    'Harga dihitung per orang per hari',
                ],
            ],
        ];

        try {
            $dbPackages = PaketWisata::query()
                ->where('is_active', true)
                ->whereIn('nama_paket', ['Kunjungan Biasa', 'Camping'])
                ->get()
                ->keyBy(fn (PaketWisata $paket) => Str::lower($paket->nama_paket));

            if ($dbPackages->has('kunjungan biasa')) {
                $defaults['visit']['price'] = (float) $dbPackages['kunjungan biasa']->harga_paket;
                $defaults['visit']['description'] = $dbPackages['kunjungan biasa']->deskripsi_paket;
            }

            if ($dbPackages->has('camping')) {
                $defaults['camping']['price'] = (float) $dbPackages['camping']->harga_paket;
                $defaults['camping']['description'] = $dbPackages['camping']->deskripsi_paket;
            }
        } catch (QueryException) {
            // Fallback ke data statis saat tabel paket belum siap.
        }

        return $defaults;
    }

    private function getPaymentOptions(): array
    {
        return [
            'bank' => [
                'bca' => 'Transfer Bank BCA',
                'bni' => 'Transfer Bank BNI',
                'bri' => 'Transfer Bank BRI',
                'mandiri' => 'Transfer Bank Mandiri',
            ],
            'ewallet' => [
                'dana' => 'DANA',
                'gopay' => 'GoPay',
                'ovo' => 'OVO',
                'shopeepay' => 'ShopeePay',
            ],
            'qris' => [
                'qris' => 'QRIS All Payment',
            ],
        ];
    }
}

