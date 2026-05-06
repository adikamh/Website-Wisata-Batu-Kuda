<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pemesanan tiket Batu Kuda untuk kunjungan biasa dan camping.">
    <title>Tiket | Batu Kuda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layout.navbar')
    @include('layout.cookie-consent')

    @if (session('status'))
        <div class="flash-banner">
            {{ session('status') }}
        </div>
    @endif

    <main class="ticket-page">
        @php($ticketHeroImage = asset('images/tiket.jpeg'))

        <section class="ticket-hero">
            <div class="ticket-hero__backdrop">
                <img src="{{ $ticketHeroImage }}" alt="Visual pemesanan tiket Batu Kuda" loading="eager">
            </div>
            <div class="ticket-hero__overlay"></div>
            <div class="container ticket-hero__content">
                <div class="ticket-hero__copy fade-up">
                    <div class="section-tag">Pemesanan Tiket</div>
                    <h1 class="section-title">Atur kunjungan Batu Kuda dalam satu halaman.</h1>
                    <p class="section-subtitle">
                        Data akun Anda langsung dipakai untuk pemesanan. Pilih paket, jumlah orang,
                        tanggal kunjungan, lalu lanjutkan ke metode pembayaran yang paling nyaman.
                    </p>
                </div>

                <div class="ticket-hero__highlights fade-up">
                    <div class="ticket-mini-card">
                        <span>Paket tersedia</span>
                        <strong>Harian & Camping</strong>
                    </div>
                    <div class="ticket-mini-card">
                        <span>Pembayaran</span>
                        <strong>Bank, E-Wallet, QRIS</strong>
                    </div>
                    <div class="ticket-mini-card">
                        <span>Status tiket</span>
                        <strong>Otomatis dibuat</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="ticket-booking">
            <div class="container ticket-layout">
                <div class="ticket-form-card fade-up">
                    <div class="ticket-card__header">
                        <div>
                            <div class="section-tag">Form Pemesanan</div>
                            <h2>Lengkapi detail tiket Anda</h2>
                        </div>
                        <p>Nama dan email diambil dari akun yang sedang login.</p>
                    </div>

                    <form action="{{ route('tiket.store') }}" method="POST" class="ticket-form">
                        @csrf

                        <div class="ticket-form-grid ticket-form-grid--identity">
                            <label class="ticket-field">
                                <span>Nama Lengkap</span>
                                <input type="text" value="{{ auth()->user()->name }}" readonly>
                            </label>

                            <label class="ticket-field">
                                <span>Email / Gmail</span>
                                <input type="email" value="{{ auth()->user()->email }}" readonly>
                            </label>

                            <label class="ticket-field ticket-field--full">
                                <span>No. Telepon</span>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->Phone) }}" placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <small class="ticket-error">{{ $message }}</small>
                                @enderror
                            </label>
                        </div>

                        @php($selectedTicketId = (int) old('ticket_category_id', array_key_first($ticketPackages)))

                        <div class="ticket-section-block">
                            <div class="ticket-block__title">
                                <h3>Pilih paket wisata</h3>
                                <p>Tentukan jenis pengalaman yang Anda inginkan.</p>
                            </div>

                            <div class="ticket-package-grid">
                                @foreach ($ticketPackages as $packageKey => $package)
                                    <label class="ticket-choice-card">
                                        <input type="radio" name="ticket_category_id" value="{{ $packageKey }}" data-ticket-type="{{ $package['type'] }}" data-ticket-name="{{ $package['name'] }}" data-ticket-price="{{ $package['price'] }}" {{ $selectedTicketId === (int) $packageKey ? 'checked' : '' }}>
                                        <div class="ticket-choice-card__content">
                                            <div class="ticket-choice-card__head">
                                                <div>
                                                    <strong>{{ $package['name'] }}</strong>
                                                    <span>{{ $package['description'] }}</span>
                                                </div>
                                                <b>Rp {{ number_format($package['price'], 0, ',', '.') }}</b>
                                            </div>

                                            <ul>
                                                @foreach ($package['features'] as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('ticket_category_id')
                                <small class="ticket-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="ticket-form-grid">
                            <label class="ticket-field">
                                <span>Jumlah Orang</span>
                                <input type="number" min="1" max="20" name="visitor_count" value="{{ old('visitor_count', 1) }}">
                                @error('visitor_count')
                                    <small class="ticket-error">{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="ticket-field">
                                <span>Tanggal Kunjungan</span>
                                <input type="date" name="visit_date" value="{{ old('visit_date', now()->toDateString()) }}" min="{{ now()->toDateString() }}">
                                @error('visit_date')
                                    <small class="ticket-error">{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="ticket-field ticket-field--checkout">
                                <span>Tanggal Keluar</span>
                                <input type="date" name="camping_end_date" value="{{ old('camping_end_date', now()->toDateString()) }}" min="{{ old('visit_date', now()->toDateString()) }}">
                                @error('camping_end_date')
                                    <small class="ticket-error">{{ $message }}</small>
                                @enderror
                            </label>
                        </div>

                        <div class="ticket-section-block">
                            <div class="ticket-block__title">
                                <h3>Metode pembayaran</h3>
                                <p>Pilih kategori pembayaran lalu tentukan metode yang diinginkan.</p>
                            </div>

                            <div class="ticket-payment-tabs">
                                @foreach ($paymentOptions as $categoryKey => $methods)
                                    <label class="ticket-payment-tab">
                                        <input type="radio" name="payment_category" value="{{ $categoryKey }}" {{ old('payment_category', 'bank') === $categoryKey ? 'checked' : '' }}>
                                        <span>{{ $categoryKey === 'bank' ? 'Bank' : ($categoryKey === 'ewallet' ? 'E-Wallet' : 'QRIS') }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('payment_category')
                                <small class="ticket-error">{{ $message }}</small>
                            @enderror

                            <div class="ticket-payment-methods">
                                @foreach ($paymentOptions as $categoryKey => $methods)
                                    <div class="ticket-payment-group {{ old('payment_category', 'bank') === $categoryKey ? 'is-active' : '' }}" data-payment-group="{{ $categoryKey }}">
                                        @foreach ($methods as $methodKey => $methodLabel)
                                            <label class="ticket-method-card">
                                                <input type="radio" name="payment_method" value="{{ $methodKey }}" {{ old('payment_method', $categoryKey === 'bank' && $methodKey === 'bca' ? 'bca' : null) === $methodKey ? 'checked' : '' }}>
                                                <span>{{ $methodLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <small class="ticket-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <label class="ticket-field">
                            <span>Catatan Tambahan</span>
                            <textarea name="notes" rows="4" placeholder="Opsional, misalnya datang rombongan keluarga atau kebutuhan khusus.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <small class="ticket-error">{{ $message }}</small>
                            @enderror
                        </label>

                        <button type="submit" class="btn-primary ticket-submit">Pesan Tiket Sekarang</button>
                    </form>
                </div>

                <aside class="ticket-summary-card fade-up">
                    <div class="ticket-card__header">
                        <div>
                            <div class="section-tag">Ringkasan</div>
                            <h2>Estimasi pembayaran</h2>
                        </div>
                        <p>Total akan menyesuaikan paket, jumlah orang, dan durasi kunjungan.</p>
                    </div>

                    <div class="ticket-summary-box" data-ticket-summary>
                        <div class="ticket-summary-row">
                            <span>Paket</span>
                            <strong data-summary-package>{{ $ticketPackages[$selectedTicketId]['name'] ?? '-' }}</strong>
                        </div>
                        <div class="ticket-summary-row">
                            <span>Jumlah orang</span>
                            <strong><span data-summary-visitors>{{ old('visitor_count', 1) }}</span> orang</strong>
                        </div>
                        <div class="ticket-summary-row">
                            <span>Durasi</span>
                            <strong><span data-summary-days>1</span> hari</strong>
                        </div>
                        <div class="ticket-summary-row">
                            <span>Harga per orang</span>
                            <strong>Rp <span data-summary-price>{{ number_format($ticketPackages[$selectedTicketId]['price'] ?? 0, 0, ',', '.') }}</span></strong>
                        </div>
                        <div class="ticket-summary-row ticket-summary-row--total">
                            <span>Total bayar</span>
                            <strong>Rp <span data-summary-total>{{ number_format(($ticketPackages[$selectedTicketId]['price'] ?? 0) * (int) old('visitor_count', 1), 0, ',', '.') }}</span></strong>
                        </div>
                    </div>

                    <div class="ticket-summary-note">
                        <h3>Catatan paket</h3>
                        <p>Tanggal keluar dipakai untuk laporan kunjungan dan perhitungan durasi tiket.</p>
                    </div>

                    @if ($recentTicket)
                        <div class="ticket-success-card">
                            <div class="section-tag">Pesanan Terbaru</div>
                            <h3>Tiket berhasil dibuat</h3>
                            <div class="ticket-success-list">
                                <div>
                                    <span>Kode Tiket</span>
                                    <strong>{{ $recentTicket['ticket_code'] }}</strong>
                                </div>
                                <div>
                                    <span>ID Transaksi</span>
                                    <strong>#{{ $recentTicket['transaction_id'] }}</strong>
                                </div>
                                <div>
                                    <span>Paket</span>
                                    <strong>{{ $recentTicket['package_name'] }}</strong>
                                </div>
                                <div>
                                    <span>Jumlah Orang</span>
                                    <strong>{{ $recentTicket['visitor_count'] }} orang</strong>
                                </div>
                                <div>
                                    <span>Pembayaran</span>
                                    <strong>{{ $recentTicket['payment_method_label'] }}</strong>
                                </div>
                                <div>
                                    <span>Total</span>
                                    <strong>Rp {{ number_format($recentTicket['total_bayar'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </section>
    </main>
</body>
</html>
