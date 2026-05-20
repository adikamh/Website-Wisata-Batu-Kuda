@extends('layout.auth')

@section('title', 'Lengkapi Akun Google · Batu Kuda Wisata')

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
@endpush

@section('content')
@php($authVisualImage = asset('images/about-main.jpeg'))

<div class="auth-layout register-layout">
    <div class="auth-form-panel">
        <div class="auth-form-wrap">
            <div class="auth-header">
                <div class="auth-icon register-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h1>Lengkapi Akun</h1>
                <p>Tambahkan data profil sebelum masuk dengan Google</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.google.complete.submit') }}" class="auth-form" id="registerForm">
                @csrf
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                <div class="google-account-preview">
                    @if (! empty($googleUser['avatar']))
                        <img src="{{ $googleUser['avatar'] }}" alt="{{ $googleUser['name'] }}">
                    @else
                        <div class="google-account-preview__initial">{{ strtoupper(substr($googleUser['name'], 0, 1)) }}</div>
                    @endif
                    <div>
                        <strong>{{ $googleUser['name'] }}</strong>
                        <span>{{ $googleUser['email'] }}</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">
                            Username
                            <span class="label-hint" id="username-status"></span>
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="username_unik"
                                value="{{ old('username', $googleUser['suggested_username'] ?? '') }}"
                                required
                                autocomplete="username"
                                pattern="[a-zA-Z0-9_]+"
                                title="Hanya huruf, angka, dan underscore"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">No. HP <span class="label-opt">(opsional)</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.56a2 2 0 0 1 1.81-2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l.77-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.27 16.92z"/>
                                </svg>
                            </span>
                            <input type="tel" id="phone" name="Phone" placeholder="08xxxxxxxxxx" value="{{ old('Phone') }}" autocomplete="tel">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Alamat / Detail Lokasi <span class="label-opt">(opsional)</span></label>
                    <div class="input-wrap input-wrap--action">
                        <span class="input-icon input-icon--static">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </span>
                        <input type="text" id="address" name="Address" placeholder="    Contoh: Cimenyan, Kabupaten Bandung" value="{{ old('Address') }}" autocomplete="address-level2">
                        <button type="button" class="location-field-btn" id="openLocationModal" aria-label="Pilih lokasi">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <p class="location-inline-help" id="locationHelp">Gunakan ikon lokasi untuk isi alamat otomatis.</p>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">Simpan & Masuk</span>
                    <span class="btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </span>
                    <span class="btn-loader" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                    </span>
                </button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="back-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Kembali ke Login
        </a>
    </div>

    <div class="auth-visual register-visual">
        <div class="visual-bg">
            <img src="{{ $authVisualImage }}" alt="Visual Batu Kuda" loading="eager">
        </div>
        <div class="visual-overlay"></div>

        <div class="visual-content">
            <a href="{{ route('home') }}" class="visual-logo">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 30L13 13L20 23L25 15L32 30H4Z" fill="#74c69d" opacity="0.95"/>
                    <path d="M20 7C20 7 27 10 25 20C23 16 19 15 18 11C17 15 14 17 12 20C10 11 18 5 20 7Z" fill="#b7e4c7"/>
                </svg>
                Batu Kuda
            </a>

            <div class="visual-steps">
                <h3>Akun Google Baru</h3>
                <div class="step-item">
                    <div class="step-num">01</div>
                    <div>
                        <h4>Username</h4>
                        <p>Dipakai untuk identitas akun di sistem Batu Kuda.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">02</div>
                    <div>
                        <h4>Kontak</h4>
                        <p>Nomor telepon membantu admin saat ada kebutuhan pemesanan.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">03</div>
                    <div>
                        <h4>Lokasi</h4>
                        <p>Alamat dan titik lokasi bisa dipakai untuk fitur rute.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="location-modal" id="locationModal" hidden>
    <div class="location-modal__backdrop" data-close-location-modal></div>
    <div class="location-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="locationModalTitle">
        <div class="location-modal__header">
            <div>
                <p class="location-modal__eyebrow">Lokasi Akun</p>
                <h2 id="locationModalTitle">Pilih lokasi Anda</h2>
            </div>
            <button type="button" class="location-modal__close" data-close-location-modal aria-label="Tutup modal lokasi">
                ×
            </button>
        </div>

        <p class="location-modal__description">
            Izinkan akses lokasi perangkat agar website bisa meminta lokasi Anda dan mengisi alamat otomatis. Anda juga tetap bisa klik titik di peta untuk memilih manual.
        </p>

        <p class="location-help location-help--modal" id="locationModalHelp">
            Menunggu izin lokasi atau pilihan titik di peta.
        </p>

        <div id="locationMap" class="location-map"></div>

        <div class="location-modal__actions">
            <button type="button" class="location-btn location-btn--muted" data-close-location-modal>
                Tutup
            </button>
            <button type="button" class="location-btn" id="confirmLocationSelection">
                Gunakan Lokasi Ini
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
@endpush
