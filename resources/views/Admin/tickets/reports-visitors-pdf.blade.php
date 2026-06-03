<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Pengunjung Batu Kuda</title>
    <style>
        @page {
            margin: 28px 24px;
        }

        body {
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.35;
        }

        .watermark {
            color: rgba(107, 114, 128, 0.16);
            font-size: 34px;
            font-weight: 700;
            left: 40px;
            position: fixed;
            right: 40px;
            text-align: center;
            text-transform: uppercase;
            top: 260px;
            transform: rotate(-24deg);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        .meta {
            color: #4b5563;
            margin-bottom: 16px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background: #eef2ff;
            color: #374151;
            font-size: 9px;
            text-align: left;
            text-transform: uppercase;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .empty {
            color: #6b7280;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $watermarkText }}</div>

    <div class="content">
        <h1>Laporan Daftar Pengunjung Batu Kuda</h1>
        <div class="meta">
            Dicetak: {{ $printedAt->format('d/m/Y H:i') }}<br>
            Diekspor oleh: {{ $exportedByUsername }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Resi</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tiket</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    @php($detail = $transaction->details->first())
                    <tr>
                        <td>{{ $detail?->start_date?->format('d/m/Y') ?? $transaction->created_at->format('d/m/Y') }}</td>
                        <td>{{ $detail?->end_date?->format('d/m/Y') ?? '-' }}</td>
                        <td>INV-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                        <td>{{ $transaction->user->email ?? '-' }}</td>
                        <td>{{ $detail?->tiketKategori?->nama_kategori ?? '-' }}</td>
                        <td>{{ $detail->quantity ?? 0 }} orang</td>
                        <td>{{ strtoupper($transaction->status_pembayaran) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">Belum ada data pengunjung.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
