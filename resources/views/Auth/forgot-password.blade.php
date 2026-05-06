@extends('layout.auth')

@section('title', 'Lupa Password · Batu Kuda Wisata')

@push('styles')
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
@endpush

@section('content')

<div class="auth-layout">
    <div class="auth-visual">
        <div class="visual-bg" style="background-image: url('{{ asset('images/login.jpeg') }}');"></div>
        <div class="visual-overlay"></div>

        <div class="visual-content">
            <a href="{{ route('home') }}" class="visual-logo">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.wwww3.org/2000/svg">
                    <path d="M4 30L13 13L20 23L25 15L32 30H4Z" fill="#74c69d" opacity="0.95"/>
                    <path d="M20 7C20 7 27 10 25 20C23 16 19 15 18 11C17 15 14 17 12 20C10 11 18 5 20 7Z" fill="#b7e4c7"/>
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                </div>
                <h1>Lupa Password</h1>
                <p>Masukan email Anda dan kami akan kirimkan OTP untuk reset password</p>
            </div>

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

            @if (session('status'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2 2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" autocomplete="email" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <span class="btn-text">Kirim OTP</span>
                    <span class="btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </button>
            </form>

            <p class="auth-switch">
                Ingat password Anda?
                <a href="{{ route('login') }}">Masuk sekarang →</a>
            </p>

            <a href="{{ route('home') }}" class="back-home">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

@endsection
