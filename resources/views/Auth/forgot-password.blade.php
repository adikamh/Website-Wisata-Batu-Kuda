@extends('layout.auth')

@section('title', 'Lupa Password · Batu Kuda Wisata')

@push('styles')
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
@endpush

@section('content')

@php($authVisualImage = asset('images/login.jpeg'))
@php($authLogoImage = asset('images/logo/favicon.png'))

<div class="auth-layout">
    <div class="auth-visual">
        <div class="visual-bg">
            <img src="{{ $authVisualImage }}" alt="Visual lupa password Batu Kuda" loading="eager">
        </div>
        <div class="visual-overlay"></div>

        <div class="visual-content">
            <a href="{{ route('home') }}" class="visual-logo">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;">
                    <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="#74c69d" opacity="0.9"/>
                    <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="#b7e4c7"/>
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
                    <img src="{{ $authLogoImage }}" alt="Logo Batu Kuda">
                </div>
                <h1>Lupa Password</h1>
                <p>Masukan email Anda dan kami akan kirimkan OTP untuk reset password</p>
            </div>

            {{-- Flash and validation displayed via x-sweet-alert in layout.auth --}}

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
