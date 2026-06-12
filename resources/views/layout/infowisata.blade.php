@extends('layout.main')

@section('title', 'Info Wisata · Batu Kuda')
@section('meta_description', 'Panduan lengkap wisata Batu Kuda: tiket, transportasi, fasilitas, dan tips berkunjung.')
@section('body_class', 'iw-body')

@push('styles')
    @vite(['resources/css/infowisata.css'])
@endpush

@section('content')

{{-- Blurry decorative blobs --}}
<div class="iw-blobs" aria-hidden="true">
    <div class="iw-blob iw-blob-1"></div>
    <div class="iw-blob iw-blob-2"></div>
    <div class="iw-blob iw-blob-3"></div>
</div>

<header class="iw-hero">
    <div class="iw-hero-bg"></div>
    <div class="iw-hero-grain"></div>
    <div class="iw-hero-content">
        <div class="iw-hero-tag">
            <span class="tag-dot"></span>
            Panduan Lengkap Wisatawan
        </div>
        <h1 class="iw-hero-title">
            Info <em>Wisata</em><br>Batu Kuda
        </h1>
        <p class="iw-hero-sub">Semua yang perlu Anda tahu sebelum, saat, dan sesudah berkunjung.</p>

        @auth
            @if(auth()->user()->role === 'admin')
            <button class="iw-btn-add" onclick="openModal('create')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Seksi Info
            </button>
            @endif
        @endauth
    </div>

    <div class="iw-hero-stats">
        <div class="iw-stat"><span class="iw-stat-n">06.00</span><span class="iw-stat-l">Buka</span></div>
        <div class="iw-stat-sep">—</div>
        <div class="iw-stat"><span class="iw-stat-n">17.00</span><span class="iw-stat-l">Tutup</span></div>
        <div class="iw-stat-sep">—</div>
        <div class="iw-stat"><span class="iw-stat-n">Rp 15k</span><span class="iw-stat-l">Tiket</span></div>
    </div>
</header>

{{-- SweetAlert2 handles flash messages globally via layout.main's x-sweet-alert component --}}

<div class="iw-tabs-wrap" id="iwTabs">
    <div class="iw-tabs">
        <button class="iw-tab active" data-filter="all">Semua</button>
        @foreach($sections->pluck('kategori')->unique()->filter() as $kat)
            <button class="iw-tab" data-filter="{{ $kat }}">{{ $kat }}</button>
        @endforeach
    </div>
</div>

<div class="iw-main">
    <div class="iw-layout">

        {{-- ── Sticky Sidebar Navigation ──────────────────── --}}
        <aside class="iw-sidebar" id="iwSidebar" aria-label="Daftar Konten">
            <div class="iw-sidebar-title">Daftar Isi</div>
            <ul class="iw-nav-list" id="sidebarNavList">
                @foreach($sections as $section)
                <li class="iw-nav-item" data-sid="{{ $section->id }}">
                    <a href="#sec-{{ $section->id }}">
                        @if($section->icon)
                            <span class="iw-nav-icon">{{ $section->icon }}</span>
                        @else
                            <span class="iw-nav-dot"></span>
                        @endif
                        <span>{{ $section->judul }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- ── Main Content ─────────────────────────────────── --}}
        <div>

            @if($sections->isEmpty())
            <div class="iw-empty">
                {{-- SVG Illustration: Mountain scene --}}
                <svg class="iw-empty-svg" viewBox="0 0 320 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#d8f3dc"/>
                            <stop offset="100%" stop-color="#f0faf3"/>
                        </linearGradient>
                        <linearGradient id="mtnGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#40916c"/>
                            <stop offset="100%" stop-color="#1a3c28"/>
                        </linearGradient>
                        <linearGradient id="mtnGrad2" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#74c69d"/>
                            <stop offset="100%" stop-color="#2d6a4f"/>
                        </linearGradient>
                    </defs>
                    <!-- Sky -->
                    <rect width="320" height="200" fill="url(#skyGrad)" rx="20"/>
                    <!-- Stars -->
                    <circle cx="40" cy="30" r="1.5" fill="#b7e4c7" opacity="0.8"/>
                    <circle cx="80" cy="18" r="1" fill="#b7e4c7" opacity="0.6"/>
                    <circle cx="160" cy="22" r="1.5" fill="#b7e4c7" opacity="0.7"/>
                    <circle cx="240" cy="14" r="1" fill="#b7e4c7" opacity="0.5"/>
                    <circle cx="290" cy="35" r="1.5" fill="#b7e4c7" opacity="0.8"/>
                    <circle cx="270" cy="20" r="1" fill="#c8a44a" opacity="0.6"/>
                    <!-- Moon -->
                    <circle cx="270" cy="45" r="18" fill="#f0d990" opacity="0.4"/>
                    <circle cx="278" cy="40" r="14" fill="url(#skyGrad)"/>
                    <!-- Background mountain -->
                    <polygon points="60,160 160,60 260,160" fill="url(#mtnGrad2)" opacity="0.5"/>
                    <!-- Foreground mountains -->
                    <polygon points="0,200 100,80 200,200" fill="url(#mtnGrad)"/>
                    <polygon points="120,200 230,90 320,200" fill="#2d6a4f" opacity="0.9"/>
                    <!-- Snow caps -->
                    <polygon points="100,80 90,105 110,105" fill="white" opacity="0.7"/>
                    <polygon points="230,90 222,112 238,112" fill="white" opacity="0.6"/>
                    <!-- Ground -->
                    <ellipse cx="160" cy="198" rx="160" ry="20" fill="#1a3c28" opacity="0.4"/>
                    <!-- Pine trees -->
                    <g opacity="0.85">
                        <polygon points="30,200 40,170 50,200" fill="#0d2818"/>
                        <polygon points="55,200 65,175 75,200" fill="#0d2818"/>
                        <polygon points="250,200 260,172 270,200" fill="#0d2818"/>
                        <polygon points="275,200 285,178 295,200" fill="#0d2818"/>
                    </g>
                    <!-- "No content" gentle cloud -->
                    <ellipse cx="160" cy="110" rx="35" ry="18" fill="white" opacity="0.35"/>
                    <circle cx="140" cy="108" r="14" fill="white" opacity="0.35"/>
                    <circle cx="178" cy="108" r="12" fill="white" opacity="0.35"/>
                </svg>
                <h3>Belum Ada Info Wisata</h3>
                <p>Konten info wisata akan segera hadir.</p>
                @auth @if(auth()->user()->role === 'admin')
                <button class="iw-btn-add" style="margin-top:1rem;" onclick="openModal('create')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Sekarang
                </button>
                @endif @endauth
            </div>
            @endif

            <div class="iw-bento" id="iwSections">
            @foreach($sections as $i => $section)

            @php
                $katLower   = strtolower($section->kategori ?? '');
                $judulLower = strtolower($section->judul ?? '');
                $isTiket    = str_contains($katLower, 'tiket') || str_contains($katLower, 'harga')
                           || str_contains($judulLower, 'tiket') || str_contains($judulLower, 'harga');
                $isJam      = str_contains($katLower, 'jam') || str_contains($katLower, 'operasional')
                           || str_contains($judulLower, 'jam') || str_contains($judulLower, 'operasional');
            @endphp

            <article
                id="sec-{{ $section->id }}"
                class="iw-section reveal{{ $isTiket ? ' tiket-card' : '' }}"
                data-kategori="{{ $section->kategori }}"
                data-id="{{ $section->id }}"
                style="--delay: {{ $i * 0.08 }}s"
            >
                <div class="iw-sec-header">
                    <div class="iw-sec-meta">
                        @if($section->icon)
                        <div class="iw-sec-icon">{{ $section->icon }}</div>
                        @endif
                        <div>
                            @if($section->kategori)
                            <span class="iw-sec-kat">{{ $section->kategori }}</span>
                            @endif
                            <h2 class="iw-sec-title">{{ $section->judul }}</h2>
                        </div>
                    </div>

                    @auth @if(auth()->user()->role === 'admin')
                    <div class="iw-sec-actions">
                        <button class="iw-act-btn edit" title="Edit seksi" onclick="openModal('edit', {{ $section->id }})">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('infowisata.destroy', $section->id) }}" class="iw-del-form">
                            @csrf @method('DELETE')
                            <button type="button" class="iw-act-btn delete" title="Hapus seksi" onclick="confirmDelete(this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                    @endif @endauth
                </div>

                @if($section->deskripsi)
                <div class="iw-sec-body">
                    <p class="iw-sec-desc">{{ $section->deskripsi }}</p>
                </div>
                @endif

                {{-- ── Jam Operasional: Visual Timeline ─────────── --}}
                @if($isJam && (!$section->poin || count($section->poin) === 0))
                <div class="iw-timeline">
                    <div class="iw-timeline-track">
                        <div class="iw-timeline-bar">
                            <div class="iw-timeline-fill"></div>
                            <div class="iw-tl-marker open">
                                <span class="iw-tl-label open">06:00</span>
                            </div>
                            <div class="iw-tl-marker close" style="left:100%">
                                <span class="iw-tl-label close">17:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="iw-timeline-slots">
                        <div class="iw-tl-slot"><span class="iw-tl-dot"></span>Senin – Jumat: 06:00 – 17:00</div>
                        <div class="iw-tl-slot"><span class="iw-tl-dot gold"></span>Sabtu – Minggu: 05:30 – 17:30</div>
                    </div>
                </div>
                @endif

                {{-- ── Poin list (grid cards / price rows) ─────── --}}
                @if($section->poin && count($section->poin) > 0)
                <div class="iw-poin-wrap">
                    @foreach($section->poin as $pi => $poin)
                    <div class="iw-poin" style="--pi: {{ $pi }}">

                        @if(!$isTiket)
                            {{-- Standard grid card --}}
                            <div class="iw-poin-num">{{ str_pad($pi+1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="iw-poin-body">
                                @if(!empty($poin['judul']))
                                <div class="iw-poin-top">
                                    <h4 class="iw-poin-title">{{ $poin['judul'] }}</h4>
                                    @auth @if(auth()->user()->role === 'admin')
                                    <div class="iw-poin-actions">
                                        <button class="iw-act-btn edit sm" title="Edit poin"
                                            onclick="openPoinModal('edit', {{ $section->id }}, {{ $pi }}, {{ json_encode($poin) }})">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('infowisata.poin.destroy', [$section->id, $pi]) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="iw-act-btn delete sm" onclick="confirmDelete(this)" title="Hapus poin">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endif @endauth
                                </div>
                                @endif
                                @if(!empty($poin['isi']))
                                <p class="iw-poin-isi">{{ $poin['isi'] }}</p>
                                @endif
                            </div>
                        @else
                            {{-- Tiket / Price Row layout: label ··· harga --}}
                            <div class="iw-poin-body">
                                <div class="iw-poin-top">
                                    @if(!empty($poin['judul']))
                                    <h4 class="iw-poin-title">{{ $poin['judul'] }}</h4>
                                    @endif
                                    <span class="iw-price-spacer"></span>
                                    @if(!empty($poin['isi']))
                                    <p class="iw-poin-isi">{{ $poin['isi'] }}</p>
                                    @endif
                                    @auth @if(auth()->user()->role === 'admin')
                                    <div class="iw-poin-actions" style="margin-left:0.5rem">
                                        <button class="iw-act-btn edit sm" title="Edit poin"
                                            onclick="openPoinModal('edit', {{ $section->id }}, {{ $pi }}, {{ json_encode($poin) }})">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('infowisata.poin.destroy', [$section->id, $pi]) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="iw-act-btn delete sm" onclick="confirmDelete(this)" title="Hapus poin">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endif @endauth
                                </div>
                            </div>
                        @endif

                    </div>
                    @endforeach

                    @auth @if(auth()->user()->role === 'admin')
                    <button class="iw-poin-add" onclick="openPoinModal('create', {{ $section->id }})">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah poin
                    </button>
                    @endif @endauth
                </div>
                @else
                    @auth @if(auth()->user()->role === 'admin')
                    <div style="padding: 0 2rem 1rem;">
                        <button class="iw-poin-add" onclick="openPoinModal('create', {{ $section->id }})">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah poin pertama
                        </button>
                    </div>
                    @endif @endauth
                @endif

                {{-- ── Gallery Carousel ────────────────────────── --}}
                @if($section->gambar && count($section->gambar) > 0)
                <div class="iw-sec-gallery">
                    <div class="iw-carousel-track" id="carousel-{{ $section->id }}">
                        @foreach($section->gambar as $gi => $img)
                        <div class="iw-gal-item" style="--gi: {{ $gi }}">
                            <img src="{{ $img }}" alt="{{ $section->judul }}" loading="lazy">
                            @auth @if(auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('infowisata.gambar.destroy', [$section->id, $gi]) }}" class="iw-gal-del">
                                @csrf @method('DELETE')
                                <button type="button" class="iw-act-btn delete sm gal" onclick="confirmDelete(this)" title="Hapus gambar">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>
                            @endif @endauth
                        </div>
                        @endforeach
                    </div>
                    @if(count($section->gambar) > 2)
                    <div class="iw-carousel-nav">
                        <button class="iw-car-btn" onclick="carouselScroll('carousel-{{ $section->id }}', -1)" title="Sebelumnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button class="iw-car-btn" onclick="carouselScroll('carousel-{{ $section->id }}', 1)" title="Berikutnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    @endif
                </div>
                @endif

            </article>
            @endforeach
            </div>

        </div>{{-- end main content --}}
    </div>{{-- end iw-layout --}}
</div>

@auth @if(auth()->user()->role === 'admin')

<div class="iw-modal-overlay" id="modalOverlay" onclick="closeAllModals()">

    <div class="iw-modal" id="sectionModal" onclick="event.stopPropagation()">
        <button class="iw-modal-close" onclick="closeAllModals()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="iw-modal-header">
            <div class="iw-modal-icon" id="modalIcon">✨</div>
            <h3 id="modalTitle">Tambah Seksi Info</h3>
            <p id="modalSub">Buat konten informasi baru untuk wisatawan</p>
        </div>
        <form id="sectionForm" method="POST" class="iw-modal-form">
            @csrf
            <span id="methodField"></span>
            <div class="iw-field-row">
                <div class="iw-field">
                    <label>Ikon <span class="opt">(emoji)</span></label>
                    <input type="text" name="icon" id="fIcon" placeholder="🏔️" maxlength="4" class="iw-input icon-input">
                </div>
                <div class="iw-field grow">
                    <label>Kategori</label>
                    <input type="text" name="kategori" id="fKategori" placeholder="Contoh: Tips & Saran..." class="iw-input" list="kategoriList">
                    <datalist id="kategoriList">
                        @foreach($sections->pluck('kategori')->unique()->filter() as $kat)
                            <option value="{{ $kat }}">
                        @endforeach
                        <option value="Informasi Umum">
                        <option value="Transportasi">
                        <option value="Fasilitas">
                        <option value="Tips & Saran">
                        <option value="Harga & Tiket">
                        <option value="Jam Operasional">
                    </datalist>
                </div>
            </div>
            <div class="iw-field">
                <label>Judul Seksi <span class="req">*</span></label>
                <input type="text" name="judul" id="fJudul" required placeholder="Contoh: Cara Menuju Batu Kuda..." class="iw-input">
            </div>
            <div class="iw-field">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="fDeskripsi" rows="4" placeholder="Penjelasan singkat..." class="iw-textarea"></textarea>
                <div class="iw-char-count"><span id="descCount">0</span> / 600</div>
            </div>
            <div class="iw-field">
                <label>Urutan Tampil</label>
                <input type="number" name="urutan" id="fUrutan" value="0" min="0" class="iw-input" style="max-width:110px;">
            </div>
            <div class="iw-modal-footer">
                <button type="button" class="iw-btn-cancel" onclick="closeAllModals()">Batal</button>
                <button type="submit" class="iw-btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span id="saveBtnText">Simpan Seksi</span>
                </button>
            </div>
        </form>
    </div>

    <div class="iw-modal" id="poinModal" style="display:none;" onclick="event.stopPropagation()">
        <button class="iw-modal-close" onclick="closeAllModals()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="iw-modal-header">
            <div class="iw-modal-icon">📝</div>
            <h3 id="poinModalTitle">Tambah Poin</h3>
            <p>Detail atau tips untuk wisatawan</p>
        </div>
        <form id="poinForm" method="POST" class="iw-modal-form">
            @csrf
            <input type="hidden" name="_method" id="poinMethodField" value="">
            <div class="iw-field">
                <label>Judul Poin <span class="opt">(opsional)</span></label>
                <input type="text" name="judul" id="fPoinJudul" placeholder="Contoh: Gunakan Sepatu Gunung..." class="iw-input">
            </div>
            <div class="iw-field">
                <label>Isi / Penjelasan</label>
                <textarea name="isi" id="fPoinIsi" rows="3" placeholder="Penjelasan detail..." class="iw-textarea"></textarea>
            </div>
            <div class="iw-modal-footer">
                <button type="button" class="iw-btn-cancel" onclick="closeAllModals()">Batal</button>
                <button type="submit" class="iw-btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Poin
                </button>
            </div>
        </form>
    </div>

</div>

<div class="iw-confirm-overlay" id="confirmOverlay">
    <div class="iw-confirm">
        <div class="iw-confirm-icon">🗑️</div>
        <h3>Hapus Item?</h3>
        <p>Tindakan ini tidak bisa dibatalkan.</p>
        <div class="iw-confirm-btns">
            <button class="iw-btn-cancel" onclick="document.getElementById('confirmOverlay').classList.remove('active')">Batal</button>
            <button class="iw-btn-delete" id="confirmDeleteBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

@endif @endauth

@endsection

@push('scripts')
    @vite(['resources/js/infowisata.js'])
@endpush