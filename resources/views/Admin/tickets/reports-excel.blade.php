<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Batu Kuda</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="9">Laporan Keuangan Batu Kuda</th>
            </tr>
            <tr>
                <th colspan="9">Dicetak: {{ now()->format('d/m/Y H:i') }}</th>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Resi</th>
                <th>Nama Pengunjung</th>
                <th>Tiket</th>
                <th>Jumlah</th>
                <th>Harga Subtotal</th>
                <th>Total Bayar</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                @php($detail = $transaction->details->first())
                <tr>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>{{ $detail?->end_date ? $detail->end_date->format('d/m/Y') : '-' }}</td>
                    <td>INV-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $detail?->tiketKategori?->nama_kategori ?? '-' }}</td>
                    <td>{{ $detail->quantity ?? 0 }}</td>
                    <td>{{ (float) ($detail->subtotal ?? 0) }}</td>
                    <td>{{ (float) $transaction->total_bayar }}</td>
                    <td>{{ $transaction->payment_method ?? '-' }}</td>
                    <td>{{ strtoupper($transaction->status_pembayaran) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada data transaksi.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6"><strong>Total Pendapatan</strong></td>
                <td><strong>{{ (float) $totalRevenue }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
