<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tiket Batu Kuda (KTP)</title>
    <style>
        /* Setelan ukuran KTP dan hilangkan margin agar background bisa full-bleed */
        @page { size: 86mm 54mm; margin: 0; }
        
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #F6F8F6; /* Warna dasar krem/kehijauan sangat lembut */
            color: #222; 
        }

        /* Layout utama menggunakan tabel untuk kompatibilitas PDF (Dompdf/WKHTML) */
        .ticket-container {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .ticket-container td {
            vertical-align: top;
        }

        /* --- Panel Kiri (Tema Hutan Pinus) --- */
        .sidebar {
            width: 32%;
            background-color: #21432E; /* Hijau Pinus Tua */
            text-align: center;
            padding: 5mm 2mm;
            color: #fff;
            border-right: 3px solid #D28F4E; /* Aksen Garis Coklat Kayu */
        }
        .logo-wrapper {
            margin-bottom: 2mm;
        }
        .logo {
            width: 16mm;
            height: 16mm;
            background-color: #ffffff;
            border-radius: 50%;
            padding: 2px;
            border: 2px solid #5C7A65;
        }
        .brand-title {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1px;
            margin-top: 2px;
        }
        .brand-subtitle {
            font-size: 6.5px;
            color: #9EBAA7; /* Hijau pucat */
        }

        /* --- Panel Kanan (Detail Tiket) --- */
        .main-content {
            width: 68%;
            padding: 4mm 5mm;
        }
        
        .header-section {
            border-bottom: 1px dashed #B8C6BE;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }
        .user-name {
            font-size: 11px;
            font-weight: bold;
            color: #193322;
            text-transform: uppercase;
        }
        .resi {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
        .resi strong {
            color: #D28F4E;
        }

        /* Tabel Detail Info */
        .info-table {
            width: 100%;
            font-size: 8.5px;
            margin-bottom: 4px;
        }
        .info-table td {
            padding: 1.5px 0;
        }
        .label {
            color: #556B5C;
            width: 40%;
        }
        .value {
            font-weight: 700;
            color: #222;
        }

        /* Badge Status Pembayaran */
        .status-badge {
            display: inline-block;
            padding: 2px 4px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 2px;
        }
        .status-lunas { background-color: #D4EDDA; color: #155724; border: 1px solid #C3E6CB; }
        .status-pending { background-color: #FFF3CD; color: #856404; border: 1px solid #FFEEBA; }
        .status-failed { background-color: #F8D7DA; color: #721C24; border: 1px solid #F5C6CB; }

        /* Footer / Total Harga */
        .footer {
            margin-top: 3px;
            padding-top: 3px;
            border-top: 1px solid #DCE3DE;
        }
        .total-price {
            font-size: 11px;
            font-weight: 900;
            color: #21432E;
            float: right;
        }
        .total-label {
            font-size: 8px;
            color: #556B5C;
            float: left;
            margin-top: 2px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $user = $transaction->user ?? null;
        $status = strtolower($transaction->status_pembayaran ?? 'pending');
        
        // Logika pewarnaan badge status
        $statusClass = 'status-pending';
        if (in_array($status, ['lunas', 'success', 'berhasil'])) {
            $statusClass = 'status-lunas';
        } elseif (in_array($status, ['gagal', 'failed', 'batal'])) {
            $statusClass = 'status-failed';
        }

        // Cek logo
        $logo = public_path('images/logo.png');
        if (! file_exists($logo)) {
            $logo = public_path('images/tiket.jpeg');
        }
        
        // Cek fallback tanggal
        $tglMasuk = $detail->start_date ? \Carbon\Carbon::parse($detail->start_date)->format('d/m/Y') : '-';
        $tglKeluar = $detail->end_date ? \Carbon\Carbon::parse($detail->end_date)->format('d/m/Y') : '-';
    @endphp

    <table class="ticket-container">
        <tr>
            <td class="sidebar">
                <div class="logo-wrapper">
                    <img src="{{ $logo }}" alt="Batu Kuda" class="logo">
                </div>
                <div class="brand-title">Batu Kuda</div>
                <div class="brand-subtitle">Manglayang Campground</div>
            </td>

            <td class="main-content">
                
                <div class="header-section">
                    <div class="user-name">{{ $user?->name ?? ($transaction->user->name ?? '-') }}</div>
                    <div class="resi">Resi: <strong>{{ $ticketCode }}</strong></div>
                </div>

                <table class="info-table">
                    <tr>
                        <td class="label">Tgl Masuk</td>
                        <td class="value">: {{ $tglMasuk }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tgl Keluar</td>
                        <td class="value">: {{ $tglKeluar }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jumlah Tiket</td>
                        <td class="value">: <strong>{{ $detail->quantity ?? '-' }}</strong> Orang</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td>: <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
                    </tr>
                </table>

                <div class="footer">
                    <span class="total-label">TOTAL BAYAR</span>
                    <span class="total-price">Rp {{ number_format($transaction->total_bayar ?? 0, 0, ',', '.') }}</span>
                </div>

            </td>
        </tr>
    </table>
</body>
</html>