@extends('layout.auth')

@section('title', 'Ubah Password · Batu Kuda Wisata')

@push('styles')
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
@endpush

@section('content')

@php($authVisualImage = asset('images/tiket.jpeg'))
@php($authLogoImage = asset('images/logo/favicon.png'))
<div class="auth-layout">
    <div class="auth-visual">
        <div class="visual-bg">
            <img src="{{ $authVisualImage }}" alt="Visual ubah password Batu Kuda" loading="eager">
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
                    "Mulailah di mana Anda berada, gunakan apa yang Anda miliki,<br>
                    lakukan apa yang bisa Anda lakukan."
                </blockquote>
                <div class="quote-loc">
                    <div class="loc-dot"></div>
                    Gunung Manglayang
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form-panel">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <img src="{{ $authLogoImage }}" alt="Logo Batu Kuda">
                </div>
                <h1>Ubah Password Baru</h1>
                <p>Masukkan password baru untuk akun Anda</p>
            </div>

            {{-- Flash and validation messages handled by x-sweet-alert in layout.auth --}}

            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap is-readonly">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password baru" required minlength="8" autocomplete="new-password">
                        <button type="button" class="toggle-pw" data-target="password" aria-label="Tampilkan password">
                            <svg class="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-bar" id="pwBar"></div>
                    </div>
                    <div class="pw-hint" id="pwHint"></div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru" required autocomplete="new-password">
                        <button type="button" class="toggle-pw" data-target="password_confirmation" aria-label="Tampilkan password">
                            <svg class="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <p class="match-hint" id="matchHint"></p>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="btn-text">Ubah Password</span>
                    <span class="btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </span>
                </button>
            </form>

            <div class="auth-switch">
                <p>Sudah ingat password?</p>
                <a href="{{ route('login') }}">Masuk sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
