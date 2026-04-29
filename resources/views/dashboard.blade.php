<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Batu Kuda - Destinasi Wisata Alam di Kabupaten Bandung, Jawa Barat">
    <title>Batu Kuda | Wisata Alam Kabupaten Bandung</title>
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

<section class="hero">
    <div class="hero-bg"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <span></span>
            Kabupaten Bandung · Jawa Barat
        </div>

        <h1>
            Batu Kuda,
            <em>Keajaiban Alam</em>
            di Kaki Manglayang
        </h1>

        <p>
            Nikmati keindahan batu raksasa berbentuk kuda, hamparan pinus hijau menyejukkan,
            dan panorama Bandung dari ketinggian yang memukau jiwa.
        </p>

        <div class="hero-cta">
            <a href="#tentang" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 8 16 12 12 16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Jelajahi Sekarang
            </a>
            <a href="#galeri" class="btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Lihat Galeri
            </a>
        </div>
    </div>

    <div class="hero-float">
        <div class="card-mini">
            <div class="num">1.200</div>
            <p>Meter di atas<br>permukaan laut</p>
        </div>
    </div>

    <div class="hero-scroll">
        <div class="scroll-line"></div>
        Scroll
    </div>
</section>

<section class="about" id="tentang">
    <div class="container">
        <div class="about-grid">
            <div class="about-img-wrap fade-up">
                <div class="about-img-main">
                    <img
                        src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=900&q=80"
                        alt="Pemandangan Batu Kuda Bandung"
                        loading="lazy"
                    >
                </div>

                <div class="about-img-accent">
                    <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&q=80" alt="Hutan Pinus">
                </div>

                <div class="badge-green">
                    <div class="num">★</div>
                    <p>Top Wisata Bandung</p>
                </div>
            </div>

            <div class="fade-up">
                <div class="section-tag">Tentang Destinasi</div>
                <h2 class="section-title">
                    Legenda Batu Raksasa
                    Berbentuk Kuda
                </h2>
                <p class="section-subtitle">
                    Batu Kuda adalah kawasan wisata alam yang terletak di kawasan hutan Perhutani, Desa Cikadut,
                    Kecamatan Cimenyan, Kabupaten Bandung. Namanya berasal dari sebuah formasi batu besar yang
                    konon menyerupai kuda yang sedang duduk — menjadi daya tarik utama yang penuh misteri dan legenda.
                </p>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 1rem; line-height: 1.75;">
                    Berada di ketinggian sekitar 1.200 mdpl di lereng Gunung Manglayang, kawasan ini menawarkan
                    udara segar, hamparan pohon pinus yang rindang, serta jalur hiking yang cocok untuk semua
                    kalangan — dari keluarga hingga petualang sejati.
                </p>

                <div class="about-features">
                    <div class="feature-item fade-up">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div>
                            <h4>Lokasi Strategis</h4>
                            <p>±25 km dari pusat Kota Bandung, mudah dijangkau kendaraan pribadi</p>
                        </div>
                    </div>
                    <div class="feature-item fade-up">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <h4>Ramah Keluarga</h4>
                            <p>Area piknik, gazebo, dan jalur trekking yang aman untuk anak-anak</p>
                        </div>
                    </div>
                    <div class="feature-item fade-up">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div>
                            <h4>Ekosistem Terlindungi</h4>
                            <p>Kawasan hutan Perhutani yang terjaga kelestariannya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item fade-up">
                <div class="stat-num">
                    <span class="counter" data-target="1200">0</span>
                    <sup>m</sup>
                </div>
                <div class="stat-label">Ketinggian mdpl</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-num">
                    <span class="counter" data-target="25">0</span>
                    <sup>km</sup>
                </div>
                <div class="stat-label">Dari Kota Bandung</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-num">
                    <span class="counter" data-target="5000">0</span>
                    <sup>+</sup>
                </div>
                <div class="stat-label">Pengunjung per Bulan</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-num">
                    <span class="counter" data-target="3">0</span>
                    <sup>km</sup>
                </div>
                <div class="stat-label">Jalur Trekking</div>
            </div>
        </div>
    </div>
</section>

<section class="gallery" id="galeri">
    <div class="container">
        <div class="gallery-header fade-up">
            <div class="section-tag" style="justify-content: center;">Galeri Foto</div>
            <h2 class="section-title">Pesona Alam Batu Kuda</h2>
            <p class="section-subtitle">
                Dari hamparan pinus yang tenang hingga panorama Bandung yang memukau — setiap sudut menyimpan keindahan.
            </p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item fade-up">
                <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=80" alt="Puncak Gunung">
                <div class="overlay"><div class="overlay-text">Panorama Puncak</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="https://images.unsplash.com/photo-1448375240586-882707db888b?w=600&q=80" alt="Hutan Pinus">
                <div class="overlay"><div class="overlay-text">Hutan Pinus</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="https://images.unsplash.com/photo-1502481851512-e9e2529bfbf9?w=700&q=80" alt="Sunrise">
                <div class="overlay"><div class="overlay-text">Sunrise Spektakuler</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="https://images.unsplash.com/photo-1540390769625-2fc3f8b1d50c?w=600&q=80" alt="Jalur Trekking">
                <div class="overlay"><div class="overlay-text">Jalur Trekking</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="https://images.unsplash.com/photo-1511884642898-4c92249e20b6?w=800&q=80" alt="Alam Bebas">
                <div class="overlay"><div class="overlay-text">Udara Segar</div></div>
            </div>
        </div>
    </div>
</section>

<section class="info-section" id="info">
    <div class="container">
        <div class="info-grid">
            <div class="fade-up">
                <div class="section-tag">Informasi</div>
                <h2 class="section-title">Detail Wisata</h2>
                <p class="section-subtitle" style="margin-bottom: 2rem;">
                    Semua yang perlu Anda ketahui sebelum mengunjungi Batu Kuda.
                </p>

                <div class="info-card">
                    <h3>Info Kunjungan</h3>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Lokasi</div>
                            <div class="info-value">Desa Cikadut, Kec. Cimenyan,<br>Kabupaten Bandung, Jawa Barat</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Jam Operasional</div>
                            <div class="info-value">Setiap Hari: 06.00 – 17.00 WIB</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Harga Tiket</div>
                            <div class="info-value">
                                Dewasa: Rp 10.000<br>
                                Anak-anak: Rp 5.000<br>
                                Parkir Motor: Rp 5.000 | Mobil: Rp 10.000
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.56a2 2 0 0 1 1.81-2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l.77-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.27 16.92z"/></svg>
                        </div>
                        <div>
                            <div class="info-label">Kontak</div>
                            <div class="info-value">Pengelola Batu Kuda<br>+62 812-3456-7890</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fade-up">
                <div class="section-tag">Panduan</div>
                <h2 class="section-title">Tips Berkunjung</h2>
                <p class="section-subtitle" style="margin-bottom: 2rem;">
                    Maksimalkan pengalaman Anda di Batu Kuda dengan persiapan yang tepat.
                </p>

                <div class="tips-list">
                    <div class="tip-item">
                        <div class="tip-num">1</div>
                        <div class="tip-text">
                            <h5>Datang Pagi Hari</h5>
                            <p>Sunrise sekitar pukul 05.30 memberikan pemandangan terbaik dan udara paling segar</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-num">2</div>
                        <div class="tip-text">
                            <h5>Gunakan Alas Kaki yang Tepat</h5>
                            <p>Jalur berbatu dan tanah lembap — sepatu gunung atau sneakers grip direkomendasikan</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-num">3</div>
                        <div class="tip-text">
                            <h5>Bawa Bekal & Air</h5>
                            <p>Warung terbatas di area ini; siapkan makanan, minuman, dan camilan dari rumah</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-num">4</div>
                        <div class="tip-text">
                            <h5>Pakai Jaket / Sweater</h5>
                            <p>Suhu di pagi hari bisa mencapai 14–18°C, cukup dingin untuk dataran Bandung</p>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-num">5</div>
                        <div class="tip-text">
                            <h5>Jaga Kebersihan Alam</h5>
                            <p>Bawa kantong sampah sendiri dan jangan meninggalkan sampah di kawasan hutan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-section" id="lokasi">
    <div class="container">
        <div class="fade-up" style="text-align:center;">
            <div class="section-tag" style="justify-content:center;">Peta Lokasi</div>
            <h2 class="section-title">Temukan Batu Kuda</h2>
            <p style="color: rgba(255,255,255,0.65); font-size: 1rem; max-width: 500px; margin: 0 auto;">
                Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung — ±25 km dari pusat Kota Bandung
            </p>
        </div>

        <div class="map-wrap fade-up">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3!2d107.7178!3d-6.8567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9004f63cd27%3A0x1cfe7ac85e83d8b4!2sBatu%20Kuda!5e0!3m2!1sid!2sid!4v1680000000000"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Peta Lokasi Batu Kuda Bandung"
            ></iframe>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="fade-up">
        <h2>Siap Menjelajahi Batu Kuda?</h2>
        <p>Daftarkan diri Anda dan dapatkan informasi terbaru, tips perjalanan, serta penawaran eksklusif wisata alam Bandung.</p>
        <a href="{{ route('register') }}" class="btn-white">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Daftar Gratis Sekarang
        </a>
    </div>
</section>

<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="/" class="nav-logo" style="display:inline-flex;">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;">
                    <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="#74c69d" opacity="0.9"/>
                    <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="#b7e4c7"/>
                </svg>
                Batu Kuda
            </a>
            <p>Wisata alam autentik di lereng Gunung Manglayang, Kabupaten Bandung. Keindahan alam yang menenangkan jiwa dan raga.</p>
        </div>
        <div>
            <h5>Navigasi</h5>
            <ul>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="#info">Info Wisata</a></li>
                <li><a href="{{ route('tiket') }}">Tiket</a></li>
                <li><a href="#lokasi">Lokasi</a></li>
            </ul>
        </div>
        <div>
            <h5>Akun</h5>
            <ul>
                <li><a href="{{ route('login') }}">Masuk</a></li>
                <li><a href="{{ route('register') }}">Daftar</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© {{ date('Y') }} Batu Kuda Wisata · Kabupaten Bandung, Jawa Barat · Dibuat dengan hati</p>
    </div>
</footer>
@include('components.chatbot')
</body>
</html>
