<?php

namespace App\Http\Controllers;

use App\Models\ETicket;
use App\Models\PaketWisata;
use App\Models\TiketKategori;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'ticket_category_id' => ['required', Rule::in(array_keys($ticketPackages))],
            'visitor_count' => ['required', 'integer', 'min:1', 'max:20'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'camping_end_date' => ['required', 'date', 'after_or_equal:visit_date'],
            'payment_category' => ['required', Rule::in(array_keys($paymentOptions))],
            'payment_method' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'ticket_category_id.required' => 'Paket wisata wajib dipilih.',
            'visitor_count.required' => 'Jumlah orang wajib diisi.',
            'visitor_count.min' => 'Jumlah orang minimal 1.',
            'visitor_count.max' => 'Jumlah orang maksimal 20.',
            'visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'camping_end_date.required' => 'Tanggal keluar wajib diisi.',
            'camping_end_date.after_or_equal' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.',
            'payment_category.required' => 'Kategori pembayaran wajib dipilih.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        if (! array_key_exists($validated['payment_method'], $paymentOptions[$validated['payment_category']])) {
            return back()
                ->withInput()
                ->withErrors(['payment_method' => 'Metode pembayaran tidak sesuai dengan kategori yang dipilih.']);
        }

        $package = $ticketPackages[$validated['ticket_category_id']];
        $packageType = $package['type'];

        $startDate = Carbon::parse($validated['visit_date']);
        $endDate = Carbon::parse($validated['camping_end_date']);
        $totalDays = (int) $startDate->diffInDays($endDate) + 1;
        $visitorCount = (int) $validated['visitor_count'];
        $totalBayar = $package['price'] * $visitorCount * $totalDays;
        $paymentMethodLabel = $paymentOptions[$validated['payment_category']][$validated['payment_method']];
        $ticketCode = 'BK-' . now()->format('YmdHis') . '-' . random_int(100, 999);

        $transaction = DB::transaction(function () use ($validated, $package, $packageType, $visitorCount, $startDate, $endDate, $totalDays, $totalBayar, $paymentMethodLabel, $ticketCode) {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'pending',
                'payment_method' => $paymentMethodLabel,
            ]);

            $detail = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'tiket_kategori_id' => $validated['ticket_category_id'],
                'quantity' => $visitorCount,
                'subtotal' => $package['price'] * $visitorCount,
                'package_type' => $packageType,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_days' => $totalDays,
                'grand_total' => $totalBayar,
            ]);

            ETicket::create([
                'transaction_detail_id' => $detail->id,
                'ticket_code' => $ticketCode,
                'qr_code_hash' => hash('sha256', $ticketCode . '|' . $transaction->id),
            ]);

            return $transaction;
        });

        return redirect()
            ->route('tiket')
            ->with('status', 'Pesanan tiket berhasil dibuat.')
            ->with('recentTicket', [
                'ticket_code' => $ticketCode,
                'transaction_id' => $transaction->id,
                'package_name' => $package['name'],
                'visitor_count' => $visitorCount,
                'payment_method_label' => $paymentMethodLabel,
                'total_bayar' => $totalBayar,
            ]);
    }

    private function ticketPackages(): array
    {
        $tickets = TiketKategori::query()
            ->orderBy('harga')
            ->get();

        if ($tickets->isEmpty()) {
            $wisata = Wisata::firstOrCreate(
                ['nama_wisata' => 'Batu Kuda'],
                [
                    'deskripsi' => 'Kawasan wisata alam Batu Kuda.',
                    'lokasi' => 'Cikadut, Cimenyan, Kabupaten Bandung, Jawa Barat',
                    'gambar_url' => asset('images/hero.jpeg'),
                ]
            );

            TiketKategori::create([
                'wisata_id' => $wisata->id,
                'nama_kategori' => 'Kunjungan Harian',
                'deskripsi' => 'Tiket masuk reguler untuk menikmati area wisata Batu Kuda.',
                'harga' => 10000,
            ]);

            $tickets = TiketKategori::query()
                ->orderBy('harga')
                ->get();
        }

        return $tickets
            ->mapWithKeys(function (TiketKategori $ticket) {
                $isCamping = str_contains(strtolower($ticket->nama_kategori), 'camping')
                    || str_contains(strtolower($ticket->nama_kategori), 'kemping');

                return [
                    $ticket->id => [
                        'id' => $ticket->id,
                        'name' => $ticket->nama_kategori,
                        'description' => $ticket->deskripsi ?: ($isCamping
                            ? 'Paket bermalam atau camping yang dibuat oleh admin.'
                            : 'Tiket kunjungan yang dibuat oleh admin.'),
                        'price' => (int) $ticket->harga,
                        'type' => $isCamping ? 'camping' : 'visit',
                        'features' => [
                            $isCamping ? 'Harga dihitung per orang per hari' : 'Harga dihitung per orang',
                            'Tiket tersedia sesuai data terbaru dari admin',
                            'Berlaku untuk kawasan wisata Batu Kuda',
                        ],
                    ],
                ];
            })
            ->all();
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
