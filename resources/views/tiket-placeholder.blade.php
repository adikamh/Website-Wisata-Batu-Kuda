<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket | Batu Kuda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layout.navbar')

    <main class="ticket-placeholder-page">
        <section class="ticket-placeholder-card fade-up">
            <div class="section-tag">Menu Tiket</div>
            <h1 class="section-title">Halaman tiket sedang dikembangkan.</h1>
            <p class="section-subtitle">
                Fitur pemesanan tiket online akan segera hadir. Untuk sementara, Anda bisa kembali menjelajahi informasi wisata Batu Kuda.
            </p>
            <div class="ticket-placeholder-actions">
                <a href="{{ route('home') }}" class="btn-primary">Kembali ke Beranda</a>
                <a href="{{ route('home') }}#info" class="btn-outline ticket-outline">Lihat Info Wisata</a>
            </div>
        </section>
    </main>
</body>
</html>
