@extends('layout.auth')

@section('title', 'Verifikasi OTP · Batu Kuda Wisata')
@section('body_class', 'auth-body otp-body')

@push('styles')
    @vite(['resources/css/otp.css', 'resources/js/otp.js'])
@endpush

@section('content')

@php($authVisualImage = asset('images/tiket.jpeg'))

<div class="auth-layout otp-layout">
    <div class="auth-visual">
        <div class="visual-bg">
            <img src="{{ $authVisualImage }}" alt="Visual verifikasi OTP Batu Kuda" loading="eager">
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

    <div class="auth-form-panel otp-panel">
        <div class="otp-card">
            <div class="auth-header">
                <div class="auth-icon otp-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"></path>
                        <path d="M21 12c0 5-4 9-9 9s-9-4-9-9 4-9 9-9 9 4 9 9z"></path>
                    </svg>
                </div>
                <h1>Verifikasi OTP</h1>
                <p>Masukkan email dan kode OTP 6 digit yang kami kirimkan.</p>
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

            <form method="POST" action="{{ route('verify.otp.submit') }}" class="auth-form" id="otpForm">
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
                        <input type="email" id="email" name="email" value="{{ $email }}" readonly autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="otp">Kode OTP</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="text" id="otp" name="otp" value="{{ old('otp') }}" placeholder="Masukkan 6 digit kode OTP" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" required>
                    </div>
                    <p class="field-caption">OTP berlaku selama 5 menit sejak dikirim.</p>
                </div>

                <button type="submit" class="btn-submit" id="otpSubmitBtn">
                    <span class="btn-text">Verifikasi Sekarang</span>
                    <span class="btn-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                    <span class="btn-loader" style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    </span>
                </button>
            </form>

            <div class="otp-actions">
                <form method="POST" action="{{ route('verify.otp.resend') }}" id="resendOtpForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button
                        type="submit"
                        class="link-button"
                        id="resendOtpButton"
                        data-countdown="60"
                        data-resent-at="{{ session('otp_resent_at', now()->timestamp) }}"
                    >
                        Kirim ulang OTP
                    </button>
                </form>
                <p class="resend-hint" id="resendHint">Kirim ulang tersedia dalam <span id="countdownValue">60</span> detik</p>
                <p class="auth-switch">
                    Sudah terverifikasi?
                    <a href="{{ route('login') }}">Masuk ke akun</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
