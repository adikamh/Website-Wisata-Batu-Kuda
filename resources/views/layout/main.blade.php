<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Batu Kuda - Destinasi Wisata Alam di Kabupaten Bandung, Jawa Barat')">
    <title>@yield('title', 'Batu Kuda | Wisata Alam Kabupaten Bandung')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body_class')">
@php
    $footerAnchor = static fn (string $section) => request()->routeIs('home') ? "#{$section}" : route('home') . "#{$section}";
@endphp

@include('layout.navbar')
@include('layout.cookie-consent')

@if (session('status'))
    <div class="flash-banner">
        {{ session('status') }}
    </div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="nav-logo" style="display:inline-flex;">
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
                <li><a href="{{ $footerAnchor('tentang') }}">Tentang</a></li>
                <li><a href="{{ route('gallery.index') }}">Galeri</a></li>
                <li><a href="{{ $footerAnchor('info') }}">Info Wisata</a></li>
                <li><a href="{{ route('tiket') }}">Tiket</a></li>
                <li><a href="{{ $footerAnchor('lokasi') }}">Lokasi</a></li>
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

@stack('scripts')
</body>
</html>
