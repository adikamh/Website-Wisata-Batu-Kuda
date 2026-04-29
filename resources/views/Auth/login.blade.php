<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · Batu Kuda Wisata</title>
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>

<body class="auth-body">

    <div class="auth-layout">
        <div class="auth-visual">
            <div class="visual-bg"></div>
            <div class="visual-overlay"></div>

            <div class="visual-content">
                <a href="{{ route('home') }}" class="visual-logo">
                    <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 30L13 13L20 23L25 15L32 30H4Z" fill="#74c69d" opacity="0.95" />
                        <path d="M20 7C20 7 27 10 25 20C23 16 19 15 18 11C17 15 14 17 12 20C10 11 18 5 20 7Z"
                            fill="#b7e4c7" />
                    </svg>
                    Batu Kuda
                </a>

                <div class="visual-quote">
                    <blockquote>
                        "Alam bukan hanya tempat kita hidup —<br>
                        alam adalah rumah kita yang sesungguhnya."
                    </blockquote>
                    <div class="quote-loc">
                        <div class="loc-dot"></div>
                        Gunung Manglayang, Kabupaten Bandung
                    </div>
                </div>

                <div class="visual-cards">
                    <div class="v-card">
                        <div class="v-card-num">1.200<span>m</span></div>
                        <div class="v-card-label">di atas laut</div>
                    </div>
                    <div class="v-card">
                        <div class="v-card-num">★ 4.8</div>
                        <div class="v-card-label">Rating wisata</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-form-wrap">
                <div class="auth-header">
                    <div class="auth-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                    </div>
                    <h1>Selamat Datang</h1>
                    <p>Masuk ke akun Batu Kuda Wisata Anda</p>
                </div>

                @if ($errors->any())
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
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
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <p>{{ session('status') }}</p>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success auto-dismiss-alert" id="successAlert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="auth-form" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="login">Email / Username</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                            <input type="text" id="login" name="login" placeholder="Masukkan email atau username"
                                value="{{ old('login', session('verification_email')) }}" autocomplete="username"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            Password
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="••••••••"
                                autocomplete="current-password" required>
                            <button type="button" class="toggle-pw" data-target="password"
                                aria-label="Tampilkan password">
                                <svg class="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="display:none">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-check" style="display: flex; align-items: center; justify-content: space-between;">
                        <label class="checkbox-label" style="margin-bottom: 0;">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Ingat saya selama 30 hari
                        </label>

                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-text">Masuk</span>
                        <span class="btn-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </span>
                        <span class="btn-loader" style="display:none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                            </svg>
                        </span>
                    </button>

                    <div class="auth-divider"><span>atau</span></div>

                    <p class="auth-switch">
                        Belum punya akun?
                        <a href="{{ route('register') }}">Daftar gratis sekarang →</a>
                    </p>
                </form>
            </div>

            <a href="{{ route('home') }}" class="back-home">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('successAlert');

            if (!alert) {
                return;
            }

            alert.style.transition = 'opacity 0.45s ease, transform 0.45s ease, margin 0.45s ease';

            window.setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                alert.style.marginBottom = '0';

                window.setTimeout(() => {
                    alert.remove();
                }, 450);
            }, 5000);
        });
    </script>
    @endif

</body>

</html>