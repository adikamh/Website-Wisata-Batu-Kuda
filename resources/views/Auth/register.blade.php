<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar · Batu Kuda Wisata</title>
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body class="auth-body">

<div class="auth-layout register-layout">

    {{-- ── PANEL KIRI (Form) ── --}}
    <div class="auth-form-panel">
        <div class="auth-form-wrap">

            <div class="auth-header">
                <div class="auth-icon register-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="23" y2="8"/>
                        <line x1="21" y1="6" x2="21" y2="10"/>
                    </svg>
                </div>
                <h1>Buat Akun Baru</h1>
                <p>Bergabung dan nikmati info wisata Batu Kuda</p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                @csrf
                {{-- Role fixed sebagai 'user' --}}
                <input type="hidden" name="role" value="user">

                <div class="form-row">
                    {{-- Nama Lengkap --}}
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" id="name" name="name" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autocomplete="name">
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label for="username">
                            Username
                            <span class="label-hint" id="username-status"></span>
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" id="username" name="username" placeholder="username_unik" value="{{ old('username') }}" required autocomplete="username" pattern="[a-zA-Z0-9_]+" title="Hanya huruf, angka, dan underscore">
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <input type="email" id="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>

                <div class="form-row">
                    {{-- No. HP --}}
                    <div class="form-group">
                        <label for="phone">No. HP <span class="label-opt">(opsional)</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.56a2 2 0 0 1 1.81-2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l.77-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.27 16.92z"/></svg>
                            </span>
                            <input type="tel" id="phone" name="Phone" placeholder="08xxxxxxxxxx" value="{{ old('Phone') }}" autocomplete="tel">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group">
                        <label for="address">Alamat <span class="label-opt">(opsional)</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <input type="text" id="address" name="Address" placeholder="Kota / Kabupaten" value="{{ old('Address') }}" autocomplete="address-level2">
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password">
                        <button type="button" class="toggle-pw" data-target="password" aria-label="Tampilkan password">
                            <svg class="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="pw-strength">
                        <div class="pw-bar" id="pwBar"></div>
                    </div>
                    <div class="pw-hint" id="pwHint"></div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
                        <button type="button" class="toggle-pw" data-target="password_confirmation" aria-label="Tampilkan password">
                            <svg class="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <p class="match-hint" id="matchHint"></p>
                </div>

                {{-- Terms --}}
                <div class="form-check">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span class="checkmark"></span>
                        Saya setuju dengan <a href="#" style="color:var(--green-fresh)">Syarat & Ketentuan</a> berlaku
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">Buat Akun</span>
                    <span class="btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                    <span class="btn-loader" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    </span>
                </button>

                <div class="auth-divider"><span>atau</span></div>

                <p class="auth-switch">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk sekarang →</a>
                </p>

            </form>
        </div>

        <a href="{{ route('home') }}" class="back-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Beranda
        </a>
    </div>

    {{-- ── PANEL KANAN (Visual) ── --}}
    <div class="auth-visual register-visual">
        <div class="visual-bg reg-bg"></div>
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
                <h3>Keuntungan Bergabung</h3>
                <div class="step-item">
                    <div class="step-num">01</div>
                    <div>
                        <h4>Info Wisata Terkini</h4>
                        <p>Dapatkan update kondisi cuaca, tiket, dan event di Batu Kuda</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">02</div>
                    <div>
                        <h4>Simpan Rencana Perjalanan</h4>
                        <p>Buat dan kelola itinerary wisata Anda dengan mudah</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">03</div>
                    <div>
                        <h4>Komunitas Petualang</h4>
                        <p>Terhubung dengan sesama pengunjung dan berbagi pengalaman</p>
                    </div>
                </div>
            </div>

            <div class="visual-quote">
                <div class="quote-loc">
                    <div class="loc-dot"></div>
                    Batu Kuda · Cimenyan · Kab. Bandung
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>