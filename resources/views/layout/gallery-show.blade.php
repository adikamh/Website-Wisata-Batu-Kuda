@extends('layout.main')

@section('title', $gallery->judul_foto . ' | Galeri Batu Kuda')
@section('meta_description', $gallery->deskripsi ?: 'Detail foto galeri Wisata Batu Kuda.')

@push('styles')
    @vite(['resources/css/gallery.css'])
@endpush

@section('content')

<section class="gallery-main gallery-detail-page">
    <div class="container">
        <div class="gallery-detail">
            <div class="gallery-detail__media">
                <img
                    src="{{ $gallery->image_url }}"
                    alt="{{ $gallery->judul_foto }}"
                    class="gallery-detail__img"
                >
            </div>

            <div class="gallery-detail__content">
                <a href="{{ route('gallery.index') }}" class="gallery-detail__back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="15 18 9 12 15 6"/></svg>
                    Kembali ke Galeri
                </a>

                <h1>{{ $gallery->judul_foto }}</h1>

                @if($gallery->deskripsi)
                    <p class="gallery-detail__desc">{{ $gallery->deskripsi }}</p>
                @endif

                <div class="gallery-detail__date">{{ $gallery->created_at->diffForHumans() }}</div>

                <div class="gallery-detail__actions">
                    <button
                        class="lbstat lbstat--like {{ $isLiked ? 'is-liked' : '' }}"
                        id="lbLikeBtn"
                        data-gallery-id="{{ $gallery->id }}"
                        data-liked="{{ $isLiked ? 'true' : 'false' }}"
                    >
                        <svg viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <span id="lbLikeCount" class="like-count">{{ $totalLike }}</span>
                    </button>
                    <div class="lbstat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span id="lbCommentCount">{{ $totalKomentar }}</span>
                    </div>
                </div>

                <div class="gallery-detail__comments" id="lbComments">
                    @forelse($gallery->komentars as $komentar)
                        @php($canDeleteKomentar = $canUpload || (int) $komentar->user_id === (int) Auth::id())
                        <div class="comment-item" data-comment-id="{{ $komentar->id }}">
                            <div class="comment-avatar">{{ strtoupper(substr($komentar->user?->name ?? 'P', 0, 1)) }}</div>
                            <div class="comment-body">
                                <div class="comment-head">
                                    <div class="comment-name">{{ $komentar->user?->name ?? 'Pengguna' }}</div>
                                    @if($canDeleteKomentar)
                                        <button class="comment-delete-btn" type="button" data-comment-delete="{{ $komentar->id }}" title="Hapus komentar" aria-label="Hapus komentar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                                <path d="M9 6V4h6v2"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <div class="comment-text">{{ $komentar->isi_komentar }}</div>
                                <div class="comment-time">{{ $komentar->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="comments-empty">Belum ada komentar. Jadilah yang pertama!</p>
                    @endforelse
                </div>

                <form class="lightbox__comment-form gallery-detail__comment-form" id="lbCommentForm">
                    @csrf
                    <input type="hidden" name="gallery_id" id="lbGalleryId" value="{{ $gallery->id }}">
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
                            <button type="submit" class="lbform__send" aria-label="Kirim komentar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="toast" id="toast" hidden>
    <svg class="toast__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
    <script>
        window.GALLERY_CONFIG = {
            isAuth: true,
            canUpload: {{ $canUpload ? 'true' : 'false' }},
            userId: {{ Auth::id() ?? 'null' }},
            csrfToken: '{{ csrf_token() }}',
            routes: {
                like: '{{ route('gallery.like', '__id__') }}',
                komentar: '{{ route('gallery.komentar', '__id__') }}',
                komentarDestroy: '{{ route('gallery.komentar.destroy', '__id__') }}',
                show: '{{ route('gallery.show', '__id__') }}',
                store: '{{ route('gallery.store') }}',
                index: '{{ route('gallery.index') }}',
            }
        };
    </script>
    @vite(['resources/js/gallery.js'])
@endpush
