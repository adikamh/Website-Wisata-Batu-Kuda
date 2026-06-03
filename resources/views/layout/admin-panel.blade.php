@extends('layout.admin')

@section('content')
    <div id="adminPanel" class="admin-shell min-h-screen bg-gray-50">
        @include('Admin.partials.sidebar')

        <div id="adminSidebarOverlay" class="admin-sidebar-overlay hidden" aria-hidden="true"></div>

        <button
            type="button"
            id="sidebarToggle"
            class="sidebar-edge-toggle"
            aria-label="Toggle sidebar"
            aria-expanded="true"
        >
            <span class="sidebar-edge-icon" aria-hidden="true">&gt;</span>
        </button>

        <div id="adminMain" class="main-content">
            <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b bg-white px-4 py-4 shadow-sm sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Admin Panel</p>
                        <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">@yield('page_title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#40916c] px-3 py-2 text-xs font-semibold text-white transition-smooth hover:bg-[#2d6a4f]">
                        <i class="fas fa-house"></i>
                        <span class="hidden md:inline">Dashboard User</span>
                    </a>

                    @php
                        $notifications = $adminTicketNotifications ?? collect([
                            [
                                'buyer' => 'Budi',
                                'quantity' => 2,
                                'time' => now()->subMinutes(5),
                            ],
                            [
                                'buyer' => 'Sari',
                                'quantity' => 1,
                                'time' => now()->subMinutes(18),
                            ],
                            [
                                'buyer' => 'Andi',
                                'quantity' => 4,
                                'time' => now()->subHour(),
                            ],
                        ]);
                    @endphp

                    <div class="notification">
                        <button type="button" id="notificationToggle" class="notification-toggle" aria-expanded="false" aria-controls="notificationDropdown">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">{{ $notifications->count() }}</span>
                            <span class="sr-only">Notifikasi</span>
                        </button>
                        <div id="notificationDropdown" class="notification-dropdown" hidden>
                            <div class="notification-head">
                                <strong>Notifikasi</strong>
                                <span>Pembelian tiket terbaru</span>
                            </div>
                            <div class="notification-list">
                                @forelse ($notifications as $notification)
                                    <div class="notification-item">
                                        <div class="notification-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div>
                                            <p>{{ $notification['buyer'] }} membeli {{ $notification['quantity'] }} tiket</p>
                                            <span>{{ $notification['time']->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="notification-empty">Belum ada pembelian tiket terbaru.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="hidden items-center gap-3 sm:flex">
                        <div class="text-right">
                            <p class="text-sm font-semibold leading-tight text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs leading-tight text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#40916c] text-white">
                            <i class="fas fa-user-shield text-sm"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                @php($hideAdminInlineAlerts = trim($__env->yieldContent('hide_admin_inline_alerts')) === 'true')

                @unless ($hideAdminInlineAlerts)
                    @if (session('status'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <p class="font-semibold">Periksa kembali input yang dikirim.</p>
                            <ul class="mt-1 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endunless

                @yield('admin_content')
            </main>
        </div>
    </div>
@endsection
