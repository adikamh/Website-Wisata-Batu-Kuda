@extends('layout.main')

@section('title', 'Galeri Foto | Batu Kuda Wisata Alam')
@section('meta_description', 'Jelajahi keindahan Batu Kuda melalui koleksi foto-foto menakjubkan dari para pengunjung.')

@push('styles')
    @vite(['resources/css/gallery.css'])
@endpush

@section('content')

<section class="gallery-hero">
    <div class="gallery-hero__bg"></div>
    <div class="gallery-hero__content">
        <div class="gallery-hero__tag fade-up">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            Galeri Foto
        </div>
        <h1 class="gallery-hero__title fade-up">Pesona Alam <em>Batu Kuda</em><br>Dalam Setiap Bingkai</h1>
        <p class="gallery-hero__sub fade-up">
            Abadikan dan bagikan keindahan alam. Setiap foto adalah cerita perjalanan yang tak terlupakan.
        </p>
        <div class="gallery-hero__stats fade-up">
            <div class="ghstat">
                <span class="ghstat__num">{{ $totalFoto }}</span>
                <span class="ghstat__lbl">Foto</span>
            </div>
            <div class="ghstat-div"></div>
            <div class="ghstat">
                <span class="ghstat__num">{{ $totalLike }}</span>
                <span class="ghstat__lbl">Total Like</span>
            </div>
            <div class="ghstat-div"></div>
            <div class="ghstat">
                <span class="ghstat__num">{{ $totalKomentar }}</span>
                <span class="ghstat__lbl">Komentar</span>
            </div>
        </div>
    </div>
    <div class="gallery-hero__scroll">
        <div class="scroll-line"></div>
        Scroll
    </div>
</section>

{{-- ─── TOOLBAR ─── --}}
<section class="gallery-toolbar">
    <div class="container">
        <div class="gtoolbar">
            {{-- Search --}}
            <div class="gtoolbar__search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    id="gallerySearch"
                    placeholder="Cari foto berdasarkan judul..."
                    value="{{ request('q') }}"
                >
                <button class="gtoolbar__search-clear" id="clearSearch" {{ !request('q') ? 'hidden' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Sort --}}
            <div class="gtoolbar__sort">
                <label>Urutkan:</label>
                <div class="sort-group">
                    <a href="{{ route('gallery.index', array_merge(request()->query(), ['sort' => 'terbaru'])) }}"
                       class="sort-btn {{ $sort === 'terbaru' ? 'active' : '' }}">Terbaru</a>
                    <a href="{{ route('gallery.index', array_merge(request()->query(), ['sort' => 'terpopuler'])) }}"
                       class="sort-btn {{ $sort === 'terpopuler' ? 'active' : '' }}">
                       <svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                       Terpopuler</a>
                    <a href="{{ route('gallery.index', array_merge(request()->query(), ['sort' => 'terbanyak_komentar'])) }}"
                       class="sort-btn {{ $sort === 'terbanyak_komentar' ? 'active' : '' }}">
                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                       Terbanyak Komentar</a>
                </div>
            </div>

            {{-- Upload (hanya admin) --}}
            @if($canUpload)
            <button class="btn-upload" id="openUploadModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Upload Foto
            </button>
            @endif
        </div>
    </div>
</section>

{{-- ─── GRID UTAMA ─── --}}
<section class="gallery-main">
    <div class="container">

        @if($fotos->isEmpty())
        <div class="gallery-empty fade-up">
            <div class="gallery-empty__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <h3>Belum Ada Foto</h3>
            <p>{{ $canUpload ? 'Jadilah yang pertama mengabadikan keindahan Batu Kuda.' : 'Galeri Batu Kuda belum memiliki foto.' }}</p>
            @if($canUpload)
            <button class="btn-upload" id="openUploadModalEmpty">Upload Foto Pertama</button>
            @endif
        </div>
        @else

        {{-- Masonry / Grid --}}
        <div class="photo-grid" id="photoGrid">
            @foreach($fotos as $foto)
            <article
                class="photo-card fade-up"
                data-id="{{ $foto->id }}"
                data-title="{{ $foto->judul_foto }}"
                data-description="{{ $foto->deskripsi ?? '' }}"
                data-image-url="{{ $foto->image_url }}"
            >
                {{-- Gambar --}}
                <div class="photo-card__img-wrap">
                    <img
                        src="{{ $foto->image_url }}"
                        alt="{{ $foto->judul_foto }}"
                        loading="lazy"
                        class="photo-card__img"
                    >
                    <div class="photo-card__overlay">
                        <button class="photo-card__view-btn" data-id="{{ $foto->id }}" aria-label="Lihat detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Info --}}
                <div class="photo-card__body">
                    <h3 class="photo-card__title">{{ $foto->judul_foto }}</h3>
                    @if($foto->deskripsi)
                    <p class="photo-card__desc">{{ \Illuminate\Support\Str::limit($foto->deskripsi, 80) }}</p>
                    @endif

                    <div class="photo-card__meta">
                        <span class="meta-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $foto->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="photo-card__actions">
                        {{-- Like --}}
                        @php($liked = (bool) ($foto->liked_by_current_user ?? false))
                        <button
                            class="action-btn action-btn--like {{ $liked ? 'is-liked' : '' }}"
                            data-gallery-id="{{ $foto->id }}"
                            data-liked="{{ $liked ? 'true' : 'false' }}"
                        >
                            <svg viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span class="like-count">{{ $foto->likes_count }}</span>
                        </button>

                        {{-- Komentar --}}
                        <button class="action-btn action-btn--comment photo-card__view-btn" data-id="{{ $foto->id }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>{{ $foto->komentars_count }}</span>
                        </button>

                        @if($canUpload)
                        <button class="action-btn action-btn--admin action-btn--edit" data-gallery-edit="{{ $foto->id }}" type="button" aria-label="Edit foto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                            <span>Edit</span>
                        </button>
                        <button class="action-btn action-btn--admin action-btn--delete" data-gallery-delete="{{ $foto->id }}" type="button" aria-label="Hapus foto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            <span>Hapus</span>
                        </button>
                        @endif

                        {{-- Share --}}
                        <button class="action-btn action-btn--share" data-url="{{ route('gallery.show', $foto->id) }}" data-title="{{ $foto->judul_foto }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        </button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($fotos->hasPages())
        <div class="gallery-pagination fade-up">
            {{ $fotos->links() }}
        </div>
        @endif

        @endif
    </div>
</section>

{{-- ─── LIGHTBOX / DETAIL MODAL ─── --}}
<div class="lightbox" id="lightbox" hidden>
    <div class="lightbox__backdrop" id="lightboxClose"></div>
    <div class="lightbox__panel">
        <button class="lightbox__close" id="lightboxCloseBtn" aria-label="Tutup">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="lightbox__inner">
            {{-- Kiri: Foto --}}
            <div class="lightbox__photo">
                <img src="" alt="" id="lightboxImg" class="lightbox__img">
                <div class="lightbox__photo-nav">
                    <button class="lbnav" id="lbPrev" aria-label="Sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button class="lbnav" id="lbNext" aria-label="Berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

            {{-- Kanan: Info + Komentar --}}
            <div class="lightbox__info">
                <div class="lightbox__info-header">
                    <h2 class="lightbox__title" id="lightboxTitle"></h2>
                    <p class="lightbox__desc" id="lightboxDesc"></p>
                    <div class="lightbox__date" id="lightboxDate"></div>
                </div>

                <div class="lightbox__stats">
                    <button class="lbstat lbstat--like" id="lbLikeBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <span id="lbLikeCount">0</span> Suka
                    </button>
                    <div class="lbstat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span id="lbCommentCount">0</span> Komentar
                    </div>
                </div>

                {{-- Daftar komentar --}}
                <div class="lightbox__comments" id="lbComments">
                    <div class="lb-comments-loading">
                        <div class="spinner"></div>
                    </div>
                </div>

                {{-- Form komentar --}}
                @auth
                <form class="lightbox__comment-form" id="lbCommentForm">
                    @csrf
                    <input type="hidden" name="gallery_id" id="lbGalleryId">
                    <div class="lbform__row">
                        <div class="lbform__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="lbform__input-wrap">
                            <textarea
                                name="isi_komentar"
                                id="lbKomentarInput"
                                placeholder="Tulis komentar..."
                                rows="2"
                                maxlength="500"
                            ></textarea>
                            <button type="submit" class="lbform__send">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="lightbox__login-prompt">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <a href="{{ route('login') }}">Masuk</a> untuk like & komentar
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- ─── UPLOAD MODAL (auth only) ─── --}}
@if($canUpload)
<div class="upload-modal" id="uploadModal" hidden>
    <div class="upload-modal__backdrop" id="uploadClose"></div>
    <div class="upload-modal__panel">
        <div class="upload-modal__head">
            <h2 id="uploadModalTitle">Upload Foto Baru</h2>
            <button class="upload-modal__close" id="uploadCloseBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form class="upload-form" id="uploadForm" enctype="multipart/form-data" data-mode="create">
            @csrf
            {{-- Drop zone --}}
            <div class="upload-dropzone" id="dropzone">
                <div class="dropzone__inner" id="dropzoneInner">
                    <div class="dropzone__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <p class="dropzone__text" id="dropzoneText">Seret foto ke sini atau <label for="fotoInput" class="dropzone__link">pilih file</label></p>
                    <span class="dropzone__hint" id="dropzoneHint">PNG, JPG, WebP · Maks 5 MB</span>
                </div>
                <img src="" alt="" id="dropzonePreview" class="dropzone__preview" hidden>
                <input type="file" id="fotoInput" name="gambar" accept="image/*" class="visually-hidden">
            </div>

            {{-- Fields --}}
            <div class="upload-fields">
                <div class="upload-field">
                    <label for="judulFoto">Judul Foto <span>*</span></label>
                    <input type="text" id="judulFoto" name="judul_foto" placeholder="cth. Sunrise di Puncak Manglayang" required maxlength="120">
                </div>
                <div class="upload-field">
                    <label for="deskripsiFoto">Deskripsi</label>
                    <textarea id="deskripsiFoto" name="deskripsi" placeholder="Ceritakan momen ini..." rows="3" maxlength="500"></textarea>
                    <span class="char-count" id="descCount">0 / 500</span>
                </div>
            </div>

            <div id="uploadError" class="upload-error" hidden></div>

            <div class="upload-actions">
                <button type="button" class="btn-upload-cancel" id="uploadCancel">Batal</button>
                <button type="submit" class="btn-upload-submit" id="uploadSubmit">
                    <span class="btn-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span id="uploadSubmitText">Upload Foto</span>
                    </span>
                    <span class="btn-loading" hidden>
                        <div class="spinner spinner--white"></div>
                        <span id="uploadLoadingText">Mengupload...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Toast --}}
<div class="toast" id="toast" hidden>
    <svg class="toast__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span id="toastMsg"></span>
</div>

@endsection

@push('scripts')
    <script>
        window.GALLERY_CONFIG = {
            isAuth: {{ Auth::check() ? 'true' : 'false' }},
            canUpload: {{ $canUpload ? 'true' : 'false' }},
            userId: {{ Auth::id() ?? 'null' }},
            csrfToken: '{{ csrf_token() }}',
            routes: {
                like: '{{ route('gallery.like', '__id__') }}',
                komentar: '{{ route('gallery.komentar', '__id__') }}',
                komentarDestroy: '{{ route('gallery.komentar.destroy', '__id__') }}',
                show: '{{ route('gallery.show', '__id__') }}',
                store: '{{ route('gallery.store') }}',
                update: '{{ route('gallery.update', '__id__') }}',
                destroy: '{{ route('gallery.destroy', '__id__') }}',
                image: '{{ route('gallery.image', '__path__') }}',
                index: '{{ route('gallery.index') }}',
            }
        };
    </script>
    @vite(['resources/js/gallery.js'])
@endpush
