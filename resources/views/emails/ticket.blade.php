<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tiket Batu Kuda</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#222 }
        .container { max-width:640px; margin:0 auto; padding:20px }
        .header { border-bottom:1px solid #eee; padding-bottom:12px; margin-bottom:18px }
        pre { background:#f8f8f8; padding:12px; border-radius:6px; overflow:auto }
        .muted { color:#666; font-size:13px }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Tiket Wisata Batu Kuda</h2>
        <div class="muted">Ini adalah tiket elektronik Anda (tanpa QR code).</div>
    </div>

    @php
        $user = $transaction->user;
        $status = $transaction->status_pembayaran ?? 'pending';
        $totalRp = 'Rp' . number_format($transaction->total_bayar ?? 0, 0, ',', '.');
    @endphp

    <pre>
Tanggal        : {{ optional($transaction->created_at)->format('d/m/Y') }}
Resi           : {{ $ticketCode }}
Username       : {{ $user->name ?? ($user->email ?? '-') }}
Status         : {{ ucfirst($status) }}
Total          : {{ $totalRp }}
Jumlah Tiket   : {{ $detail->quantity ?? '-' }}
    </pre>

    <p><strong>Tanggal Masuk:</strong> {{ optional($detail->start_date ? \Carbon\Carbon::parse($detail->start_date) : null)->format('d/m/Y') ?? '-' }}</p>
    <p><strong>Tanggal Keluar:</strong> {{ optional($detail->end_date ? \Carbon\Carbon::parse($detail->end_date) : null)->format('d/m/Y') ?? '-' }}</p>
    <p><strong>Nama:</strong> {{ $user->name ?? ($user->email ?? '-') }}</p>

    @if(!empty($rentalItems))
        <p><strong>Fasilitas sewa:</strong></p>
        <ul>
            @foreach($rentalItems as $r)
                <li>{{ $r['name'] ?? 'N/A' }} x{{ $r['quantity'] ?? 0 }}</li>
            @endforeach
        </ul>
    @endif

    <hr>
    <p class="muted">Terima kasih telah memesan tiket Batu Kuda. Silakan simpan email ini sebagai bukti pembelian.</p>
</div>
</body>
</html>
