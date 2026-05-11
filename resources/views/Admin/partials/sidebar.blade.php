@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'fas fa-chart-pie',
        ],
        [
            'label' => 'Data Pengguna',
            'route' => 'admin.users',
            'icon' => 'fas fa-users',
        ],
        [
            'label' => 'Tiket',
            'route' => 'admin.tickets',
            'icon' => 'fas fa-ticket-alt',
        ],
    ];
@endphp

<aside id="adminSidebar" class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo-wrap">
            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="sidebar-logo">
                <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="#74c69d" opacity="0.9"/>
                <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="#b7e4c7"/>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <div class="sidebar-brand-name">Batu Kuda</div>
            <p class="sidebar-brand-sub">Panel Kontrol</p>
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Menu admin">
        <div class="sidebar-heading">Menu Utama</div>
        @foreach ($navItems as $item)
            <a
                href="{{ route($item['route']) }}"
                class="sidebar-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                title="{{ $item['label'] }}"
            >
                <i class="sidebar-link-icon {{ $item['icon'] }}"></i>
                <span class="menu-text">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" title="Logout">
                <i class="sidebar-link-icon fas fa-sign-out-alt"></i>
                <span class="menu-text">Logout</span>
            </button>
        </form>
    </div>
</aside>
