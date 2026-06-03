<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Batu Kuda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative;
        }

        .watermark {
            color: #d1d5db;
            font-size: 42px;
            font-weight: 700;
            left: 80px;
            opacity: 0.24;
            position: absolute;
            text-align: center;
            top: 130px;
            transform: rotate(-24deg);
            width: 900px;
            z-index: 0;
        }

        table {
            position: relative;
            z-index: 1;
        }

        th {
            background-color: #e8f5e9;
            font-weight: bold;
        }

        .watermark-row {
            color: #9ca3af;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $watermarkText }}</div>

    <table border="1">
        <thead>
            <tr>
                <th colspan="12">Laporan Keuangan Batu Kuda</th>
            </tr>
            <tr>
                <th colspan="12">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</th>
            </tr>
            <tr>
                <th colspan="12" class="watermark-row">Diekspor oleh: {{ $exportedByUsername }}</th>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Resi</th>
                <th>Nama Pengunjung</th>
                <th>Tiket</th>
                <th>Fasilitas Sewa</th>
                <th>Jumlah</th>
                <th>Harga Subtotal</th>
                <th>Subtotal Fasilitas</th>
                <th>Total Bayar</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                @php($detail = $transaction->details->first())
                @php($rentalItems = $transaction->rentalItems ?? collect())
                @php($rentalText = $rentalItems->isNotEmpty() ? $rentalItems->map(fn ($item) => $item->facility_name . ' x' . $item->quantity . ' = Rp ' . number_format($item->subtotal, 0, ',', '.'))->implode(', ') : '-')
                <tr>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>{{ $detail?->end_date ? $detail->end_date->format('d/m/Y') : '-' }}</td>
                    <td>INV-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $detail?->tiketKategori?->nama_kategori ?? '-' }}</td>
                    <td>{{ $rentalText }}</td>
                    <td>{{ $detail->quantity ?? 0 }}</td>
                    <td>{{ (float) ($detail->subtotal ?? 0) }}</td>
                    <td>{{ (float) $rentalItems->sum('subtotal') }}</td>
                    <td>{{ (float) $transaction->total_bayar }}</td>
                    <td>{{ $transaction->payment_method ?? '-' }}</td>
                    <td>{{ strtoupper($transaction->status_pembayaran) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">Belum ada data transaksi.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="9"><strong>Total Pendapatan</strong></td>
                <td><strong>{{ (float) $totalRevenue }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
