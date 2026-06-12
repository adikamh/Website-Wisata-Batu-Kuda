<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tiket Batu Kuda (KTP)</title>
    <style>
        @page { size: 86mm 54mm; margin: 4mm }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 9px; color: #222; }
        .card { width:100%; height:100%; display:flex; align-items:center; gap:8px }
        .logo { width:24mm; height:auto }
        .right { flex:1 }
        .muted { color:#666; font-size:8px }
        .title { font-size:11px; font-weight:700 }
        .small { font-size:9px }
        .row { display:flex; justify-content:space-between; gap:6px }
    </style>
</head>
<body>
    @php
        $user = $transaction->user ?? null;
        $status = $transaction->status_pembayaran ?? 'pending';
    @endphp

    <div class="card">
        <div style="width:30%; text-align:center">
            @php
                $logo = public_path('images/logo.png');
                if (! file_exists($logo)) {
                    $logo = public_path('images/tiket.jpeg');
                }
            @endphp
            <img src="{{ $logo }}" alt="Batu Kuda" class="logo">
            <div class="muted">Tiket Batu Kuda</div>
        </div>

        <div class="right">
            <div class="title">{{ $user?->name ?? ($transaction->user->name ?? '-') }}</div>
            <div class="muted">Resi: <strong>{{ $ticketCode }}</strong></div>
            <div class="row small" style="margin-top:6px">
                <div>Jumlah: <strong>{{ $detail->quantity ?? '-' }}</strong></div>
                <div>{{ ucfirst($status) }}</div>
            </div>
            <div class="small" style="margin-top:6px">Total: Rp {{ number_format($transaction->total_bayar ?? 0, 0, ',', '.') }}</div>

            <div style="margin-top:6px; font-size:9px">
                <div><strong>Tgl Masuk:</strong> {{ optional($detail->start_date ? \Carbon\Carbon::parse($detail->start_date) : null)->format('d/m/Y') ?? '-' }}</div>
                <div><strong>Tgl Keluar:</strong> {{ optional($detail->end_date ? \Carbon\Carbon::parse($detail->end_date) : null)->format('d/m/Y') ?? '-' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
