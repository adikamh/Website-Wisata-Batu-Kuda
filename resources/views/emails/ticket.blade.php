<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tiket Batu Kuda</title>
    @php
        $user = $transaction->user;
        $status = $transaction->status_pembayaran ?? 'pending';
        $totalRp = 'Rp ' . number_format($transaction->total_bayar ?? 0, 0, ',', '.');
        $startDate = optional($detail->start_date ? \Carbon\Carbon::parse($detail->start_date) : null)->format('d M Y') ?? '-';
        $endDate = optional($detail->end_date ? \Carbon\Carbon::parse($detail->end_date) : null)->format('d M Y') ?? '-';
        $guestName = $user->name ?? ($user->email ?? '-');
        $statusLabel = match(strtolower($status)) {
            'paid', 'lunas', 'success' => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            default => ucfirst($status),
        };
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0e1a12;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        /* ── Outer wrapper ── */
        .ticket-wrap {
            width: 100%;
            max-width: 560px;
        }

        /* ── Header bar ── */
        .ticket-header {
            background: linear-gradient(135deg, #1a3d20 0%, #2d6a35 60%, #3a8c42 100%);
            border-radius: 20px 20px 0 0;
            padding: 32px 36px 28px;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 20px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .venue-label {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #a8d5a2;
            margin-bottom: 6px;
        }

        .venue-name {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .venue-tagline {
            font-size: 12px;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.5px;
        }

        .header-badge {
            position: absolute;
            top: 28px; right: 36px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
            backdrop-filter: blur(4px);
        }

        /* ── Sawtooth divider ── */
        .tear {
            background: #1d2e1f;
            display: flex;
            align-items: center;
            position: relative;
            height: 28px;
        }
        .tear::before, .tear::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            width: 28px; height: 28px;
            background: #0e1a12;
            border-radius: 50%;
        }
        .tear::before { left: -14px; }
        .tear::after  { right: -14px; }

        .tear-dashes {
            flex: 1;
            margin: 0 28px;
            border-top: 2px dashed rgba(255,255,255,0.12);
        }

        /* ── Body ── */
        .ticket-body {
            background: #1d2e1f;
            padding: 28px 36px 32px;
        }

        /* Status pill */
        @php $statusColor = match(strtolower($status)) {
            'paid', 'lunas', 'success' => ['bg'=>'#0f3a1b','text'=>'#4ade80','dot'=>'#22c55e'],
            'pending'                  => ['bg'=>'#3a2e0a','text'=>'#fbbf24','dot'=>'#f59e0b'],
            default                    => ['bg'=>'#3a1010','text'=>'#f87171','dot'=>'#ef4444'],
        }; @endphp

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            background: {{ $statusColor['bg'] }};
            color: {{ $statusColor['text'] }};
        }
        .status-pill .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: {{ $statusColor['dot'] }};
        }

        .resi-code {
            font-family: 'Inter', monospace;
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            letter-spacing: 1px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .info-item label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 5px;
        }

        .info-item .value {
            font-size: 14px;
            font-weight: 500;
            color: #e8f5e3;
        }

        .info-item .value.highlight {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: #6dd56d;
        }

        /* Divider */
        .section-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 20px 0;
        }

        /* Date range */
        .date-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .date-box {
            flex: 1;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 12px 16px;
        }

        .date-box label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            display: block;
            margin-bottom: 4px;
        }

        .date-box .date-val {
            font-size: 15px;
            font-weight: 600;
            color: #c8ebc4;
        }

        .date-arrow {
            color: rgba(255,255,255,0.25);
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Rentals */
        .rental-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 10px;
        }

        .rental-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .rental-list li {
            background: rgba(100,200,100,0.08);
            border: 1px solid rgba(100,200,100,0.18);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #9dcf99;
        }

        /* ── Footer bar ── */
        .ticket-footer {
            background: #162419;
            border-radius: 0 0 20px 20px;
            padding: 18px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-note {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            max-width: 300px;
            line-height: 1.5;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            font-weight: 700;
            color: rgba(255,255,255,0.2);
            text-align: right;
            line-height: 1.2;
        }

        /* ── Print / responsive ── */
        @media (max-width: 480px) {
            .ticket-header { padding: 24px 22px 20px; }
            .ticket-body { padding: 22px 22px 26px; }
            .ticket-footer { padding: 14px 22px; flex-direction: column; gap: 10px; text-align: center; }
            .header-badge { display: none; }
            .info-grid { grid-template-columns: 1fr; gap: 14px; }
            .venue-name { font-size: 26px; }
        }

        @media print {
            body { background: #fff; }
            .ticket-header { background: #1a3d20 !important; -webkit-print-color-adjust: exact; }
            .ticket-body { background: #1d2e1f !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="ticket-wrap">

    {{-- ── Header ── --}}
    <div class="ticket-header">
        <div class="venue-label">E-Tiket Wisata</div>
        <div class="venue-name">Batu Kuda</div>
        <div class="venue-tagline">Gunung Manglayang · Jawa Barat</div>
        <div class="header-badge">{{ optional($transaction->created_at)->format('d M Y') }}</div>
    </div>

    {{-- ── Tear divider ── --}}
    <div class="tear"><div class="tear-dashes"></div></div>

    {{-- ── Body ── --}}
    <div class="ticket-body">

        {{-- Status + Resi --}}
        <div class="status-row">
            <span class="status-pill">
                <span class="dot"></span>
                {{ $statusLabel }}
            </span>
            <span class="resi-code">#{{ $ticketCode }}</span>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-item">
                <label>Nama Pemesan</label>
                <div class="value">{{ $guestName }}</div>
            </div>
            <div class="info-item">
                <label>Jumlah Tiket</label>
                <div class="value">{{ $detail->quantity ?? '-' }} orang</div>
            </div>
            <div class="info-item">
                <label>Total Bayar</label>
                <div class="value highlight">{{ $totalRp }}</div>
            </div>
            <div class="info-item">
                <label>Tgl. Pemesanan</label>
                <div class="value">{{ optional($transaction->created_at)->format('d M Y') }}</div>
            </div>
        </div>

        <hr class="section-divider">

        {{-- Date range --}}
        <div class="date-row">
            <div class="date-box">
                <label>Tanggal Masuk</label>
                <div class="date-val">{{ $startDate }}</div>
            </div>
            <div class="date-arrow">→</div>
            <div class="date-box">
                <label>Tanggal Keluar</label>
                <div class="date-val">{{ $endDate }}</div>
            </div>
        </div>

        {{-- Rental items --}}
        @if(!empty($rentalItems))
            <hr class="section-divider">
            <div class="rental-title">Fasilitas Sewa</div>
            <ul class="rental-list">
                @foreach($rentalItems as $r)
                    <li>{{ $r['name'] ?? 'N/A' }} &times; {{ $r['quantity'] ?? 0 }}</li>
                @endforeach
            </ul>
        @endif

    </div>

    {{-- ── Footer ── --}}
    <div class="ticket-footer">
        <div class="footer-note">Simpan tiket ini sebagai bukti pembelian. Tunjukkan kepada petugas saat memasuki kawasan wisata.</div>
        <div class="footer-logo">Batu<br>Kuda</div>
    </div>

</div>
</body>
</html>
