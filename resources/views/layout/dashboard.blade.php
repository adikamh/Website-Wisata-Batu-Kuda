@extends('layout.main')

@section('title', 'Batu Kuda | Wisata Alam Kabupaten Bandung')

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
@endpush

@section('content')

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
            <a href="{{ route('gallery.index') }}" class="btn-outline">
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
                        src="{{ asset('images/about-main.jpeg') }}"
                        alt="Pemandangan Batu Kuda Bandung"
                        loading="lazy"
                    >
                </div>

                <div class="about-img-accent">
                    <img src="{{ asset('images/about-accent.jpeg') }}" alt="Hutan Pinus">
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
                <img src="{{ asset('images/gallery-1.jpeg') }}" alt="Puncak Gunung">
                <div class="overlay"><div class="overlay-text">Panorama Puncak</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="{{ asset('images/gallery-2.jpeg') }}" alt="Hutan Pinus">
                <div class="overlay"><div class="overlay-text">Hutan Pinus</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="{{ asset('images/gallery-3.jpeg') }}" alt="Sunrise">
                <div class="overlay"><div class="overlay-text">Sunrise Spektakuler</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="{{ asset('images/gallery-4.jpeg') }}" alt="Jalur Trekking">
                <div class="overlay"><div class="overlay-text">Jalur Trekking</div></div>
            </div>
            <div class="gallery-item fade-up">
                <img src="{{ asset('images/gallery-5.jpeg') }}" alt="Alam Bebas">
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

@php
    $mapUserAddress = Auth::check() ? (Auth::user()->Address ?? '') : '';
    $mapUserLatitude = Auth::check() ? Auth::user()->latitude : null;
    $mapUserLongitude = Auth::check() ? Auth::user()->longitude : null;
@endphp

<section class="map-section" id="lokasi">
    <div class="container">
        <div class="fade-up" style="text-align:center;">
            <div class="section-tag" style="justify-content:center;">Peta Lokasi</div>
            <h2 class="section-title">{{ Auth::check() ? 'Jalur Menuju Batu Kuda' : 'Temukan Batu Kuda' }}</h2>
            <p style="color: rgba(255,255,255,0.65); font-size: 1rem; max-width: 500px; margin: 0 auto;">
                @auth
                    Jalur ditampilkan dari alamat akun Anda menuju Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung.
                @else
                    Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung — ±25 km dari pusat Kota Bandung
                @endauth
            </p>
        </div>

        <div class="map-wrap fade-up">
            <div
                id="batuKudaMap"
                class="leaflet-map"
                data-is-authenticated="{{ Auth::check() ? 'true' : 'false' }}"
                data-user-address="{{ $mapUserAddress }}"
                data-user-lat="{{ $mapUserLatitude ?? '' }}"
                data-user-lng="{{ $mapUserLongitude ?? '' }}"
                data-destination-name="Wisata Batu Kuda"
                data-destination-lat="-6.8567"
                data-destination-lng="107.7178"
            ></div>
            <div class="map-route-status" id="mapRouteStatus">
                {{ Auth::check() ? 'Memuat jalur dari lokasi akun...' : 'Menampilkan lokasi Batu Kuda.' }}
            </div>
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

@endsection

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mapElement = document.getElementById('batuKudaMap');
            const statusElement = document.getElementById('mapRouteStatus');

            if (!mapElement || typeof window.L === 'undefined') {
                return;
            }

            const destination = [
                Number(mapElement.dataset.destinationLat),
                Number(mapElement.dataset.destinationLng),
            ];
            const destinationName = mapElement.dataset.destinationName || 'Batu Kuda';
            const isAuthenticated = mapElement.dataset.isAuthenticated === 'true';
            const userAddress = (mapElement.dataset.userAddress || '').trim();
            const userLatitude = Number.parseFloat(mapElement.dataset.userLat || '');
            const userLongitude = Number.parseFloat(mapElement.dataset.userLng || '');
            const map = L.map(mapElement, {
                scrollWheelZoom: false,
            }).setView(destination, 14);
            let routeTimeMarker = null;

            const setStatus = (message) => {
                if (statusElement) {
                    statusElement.textContent = message;
                }
            };

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            L.marker(destination)
                .addTo(map)
                .bindPopup(`<strong>${destinationName}</strong><br>Desa Cikadut, Cimenyan`)
                .openPopup();

            const formatDuration = (seconds) => {
                if (!Number.isFinite(seconds) || seconds <= 0) {
                    return '-';
                }

                const totalMinutes = Math.round(seconds / 60);
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;

                if (hours <= 0) {
                    return `${Math.max(totalMinutes, 1)} menit`;
                }

                if (minutes === 0) {
                    return `${hours} jam`;
                }

                return `${hours} jam ${minutes} menit`;
            };

            const updateRouteTimeLabel = (coordinates, durationText) => {
                if (!Array.isArray(coordinates) || coordinates.length === 0 || !durationText || durationText === '-') {
                    return;
                }

                if (routeTimeMarker) {
                    map.removeLayer(routeTimeMarker);
                }

                const middlePoint = coordinates[Math.floor(coordinates.length / 2)];

                routeTimeMarker = L.marker(middlePoint, {
                    interactive: false,
                    icon: L.divIcon({
                        className: 'map-route-time-label',
                        html: `<span>${durationText}</span>`,
                        iconSize: null,
                    }),
                }).addTo(map);
            };

            const calculateDirectDistance = (origin, target) => {
                const toRadians = (value) => (value * Math.PI) / 180;
                const earthRadius = 6371000;
                const latDiff = toRadians(target[0] - origin[0]);
                const lngDiff = toRadians(target[1] - origin[1]);
                const startLat = toRadians(origin[0]);
                const endLat = toRadians(target[0]);

                const a = Math.sin(latDiff / 2) ** 2
                    + Math.cos(startLat) * Math.cos(endLat) * Math.sin(lngDiff / 2) ** 2;

                return 2 * earthRadius * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            };

            const estimateDurationFromDistance = (meters) => {
                if (!Number.isFinite(meters) || meters <= 0) {
                    return null;
                }

                const averageDrivingSpeedMetersPerSecond = 8.33;
                const terrainFactor = 1.35;

                return (meters * terrainFactor) / averageDrivingSpeedMetersPerSecond;
            };

            const geocodeAddress = async (address) => {
                const params = new URLSearchParams({
                    format: 'jsonv2',
                    limit: '1',
                    q: address,
                    countrycodes: 'id',
                });
                const response = await fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Alamat tidak bisa diproses.');
                }

                const results = await response.json();

                if (!Array.isArray(results) || results.length === 0) {
                    throw new Error('Alamat akun tidak ditemukan di peta.');
                }

                return [Number(results[0].lat), Number(results[0].lon)];
            };

            const drawRoute = async (origin) => {
                const routeUrl = [
                    'https://router.project-osrm.org/route/v1/driving',
                    `${origin[1]},${origin[0]};${destination[1]},${destination[0]}`,
                ].join('/');
                const params = new URLSearchParams({
                    overview: 'full',
                    geometries: 'geojson',
                });
                const response = await fetch(`${routeUrl}?${params.toString()}`);

                if (!response.ok) {
                    throw new Error('Rute jalan tidak tersedia.');
                }

                const data = await response.json();
                const route = data.routes?.[0];
                const coordinates = route?.geometry?.coordinates;

                if (!Array.isArray(coordinates) || coordinates.length === 0) {
                    throw new Error('Data rute kosong.');
                }

                const routeLine = coordinates.map(([lng, lat]) => [lat, lng]);

                return {
                    coordinates: routeLine,
                    layer: L.polyline(routeLine, {
                        color: '#3b82f6',
                        weight: 6,
                        opacity: 0.95,
                        lineCap: 'round',
                        lineJoin: 'round',
                    }).addTo(map),
                    distance: route?.distance ?? null,
                    duration: route?.duration ?? null,
                };
            };

            const drawFallbackLine = (origin) => {
                const fallbackCoordinates = [origin, destination];

                return {
                    coordinates: fallbackCoordinates,
                    layer: L.polyline(fallbackCoordinates, {
                        color: '#60a5fa',
                        weight: 5,
                        opacity: 0.92,
                        dashArray: '12 8',
                    }).addTo(map),
                };
            };

            const initUserRoute = async () => {
                if (!isAuthenticated) {
                    setStatus('Menampilkan lokasi Batu Kuda.');
                    return;
                }

                if (!userAddress && (!Number.isFinite(userLatitude) || !Number.isFinite(userLongitude))) {
                    setStatus('Alamat akun belum tersedia. Lengkapi alamat untuk menampilkan jalur dari lokasi Anda.');
                    return;
                }

                try {
                    let origin = null;

                    if (Number.isFinite(userLatitude) && Number.isFinite(userLongitude)) {
                        origin = [userLatitude, userLongitude];
                        setStatus('Menggunakan lokasi akun yang tersimpan...');
                    } else {
                        setStatus('Mencari lokasi akun Anda...');
                        origin = await geocodeAddress(userAddress);
                    }

                    L.marker(origin)
                        .addTo(map)
                        .bindPopup('<strong>Lokasi Anda</strong><br>Berdasarkan alamat akun');

                    let routeLayer;

                    try {
                        setStatus('Membuat jalur menuju Batu Kuda...');
                        const routeResult = await drawRoute(origin);
                        routeLayer = routeResult.layer;
                        updateRouteTimeLabel(routeResult.coordinates, formatDuration(routeResult.duration));
                        setStatus('Jalur dari lokasi akun menuju Batu Kuda berhasil ditampilkan.');
                    } catch (error) {
                        const fallbackResult = drawFallbackLine(origin);
                        routeLayer = fallbackResult.layer;
                        const directDistance = calculateDirectDistance(origin, destination);
                        const estimatedDuration = estimateDurationFromDistance(directDistance);
                        updateRouteTimeLabel(fallbackResult.coordinates, formatDuration(estimatedDuration));
                        setStatus('Rute jalan detail belum tersedia, jadi ditampilkan garis arah biru dan estimasi perjalanan.');
                    }

                    map.fitBounds(routeLayer.getBounds(), {
                        padding: [36, 36],
                    });
                } catch (error) {
                    setStatus(error.message || 'Lokasi akun belum bisa ditemukan. Menampilkan lokasi Batu Kuda saja.');
                    map.setView(destination, 14);
                }
            };

            initUserRoute();
        });
    </script>
@endpush
