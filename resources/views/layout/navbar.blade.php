@php
    $isHome = request()->routeIs('home');
    $anchor = static fn (string $section) => $isHome ? "#{$section}" : route('home') . "#{$section}";
@endphp

<nav id="navbar">
    <a href="{{ route('home') }}" class="nav-logo">
        <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="#74c69d" opacity="0.9"/>
            <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="#b7e4c7"/>
        </svg>
        Batu Kuda
    </a>

    <button type="button" class="nav-toggle" id="navToggle" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="navLinks">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-shell" id="navLinks">
        <ul class="nav-links">
            <li><a href="{{ $anchor('tentang') }}" data-nav-link data-section="tentang" class="{{ $isHome ? 'is-home-link' : '' }}">Tentang</a></li>
            <li><a href="{{ route('gallery.index') }}" data-nav-link class="{{ request()->routeIs('gallery.*') ? 'is-active' : '' }}">Galeri</a></li>
            <li><a href="{{ $anchor('info') }}" data-nav-link data-section="info" class="{{ $isHome ? 'is-home-link' : '' }}">Info Wisata</a></li>
            <li><a href="{{ route('tiket') }}" data-nav-link class="{{ request()->routeIs('tiket') ? 'is-active' : '' }}">Tiket</a></li>
            <li><a href="{{ $anchor('lokasi') }}" data-nav-link data-section="lokasi" class="{{ $isHome ? 'is-home-link' : '' }}">Lokasi</a></li>
        </ul>

        @if(Auth::check())
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-login btn-admin-dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard Admin
                </a>
            @endif

            <div class="nav-user dropdown">
                <button type="button" class="btn-login btn-user" id="userDropdownButton" aria-expanded="false" aria-controls="userDropdown">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    {{ Auth::user()->name }}
                    <svg class="caret-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div id="userDropdown" class="dropdown-menu">
                    <div class="dropdown-head">
                        <p>{{ Auth::user()->name }}</p>
                        <span>{{ Auth::user()->email }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-action">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-login">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk
            </a>
        @endif
    </div>
</nav>
