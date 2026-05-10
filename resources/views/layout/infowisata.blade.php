@extends('layout.main')

@section('title', 'Info Wisata · Batu Kuda')
@section('meta_description', 'Panduan lengkap wisata Batu Kuda: tiket, transportasi, fasilitas, dan tips berkunjung.')
@section('body_class', 'iw-body')

@push('styles')
    @vite(['resources/css/infowisata.css'])
@endpush

@section('content')

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

@if(session('success'))
<div class="iw-flash success" id="flashMsg">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
    <button onclick="this.parentElement.remove()">×</button>
</div>
@endif
@if(session('error'))
<div class="iw-flash error" id="flashMsg">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    {{ session('error') }}
    <button onclick="this.parentElement.remove()">×</button>
</div>
@endif

<div class="iw-tabs-wrap" id="iwTabs">
    <div class="iw-tabs">
        <button class="iw-tab active" data-filter="all">Semua</button>
        @foreach($sections->pluck('kategori')->unique()->filter() as $kat)
            <button class="iw-tab" data-filter="{{ $kat }}">{{ $kat }}</button>
        @endforeach
    </div>
</div>

<div class="iw-main">
    <div class="iw-container">

        @if($sections->isEmpty())
        <div class="iw-empty">
            <div class="iw-empty-icon">🏔️</div>
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

        <div class="iw-sections" id="iwSections">
        @foreach($sections as $i => $section)
        <article
            class="iw-section reveal"
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

            @if($section->poin && count($section->poin) > 0)
            <div class="iw-poin-wrap">
                @foreach($section->poin as $pi => $poin)
                <div class="iw-poin" style="--pi: {{ $pi }}">
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

            @if($section->gambar && count($section->gambar) > 0)
            <div class="iw-sec-gallery">
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
            @endif

        </article>
        @endforeach
        </div>

    </div>
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