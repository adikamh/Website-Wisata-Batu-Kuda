/**
 * gallery.js  ·  Batu Kuda – Halaman Galeri
 * Handles: lightbox, like AJAX, komentar AJAX, upload, search, share
 */

document.addEventListener('DOMContentLoaded', () => {
    const CFG   = window.GALLERY_CONFIG || {};
    const CSRF  = CFG.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';
    const ROUTES = CFG.routes || {};

    /* ─── Helpers ─── */
    const $  = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];
    const url = (template, id) => template.replace('__id__', id);
    const imageRoute = (path) => {
        const encodedPath = String(path)
            .split('/')
            .map((segment) => encodeURIComponent(segment))
            .join('/');

        return ROUTES.image ? ROUTES.image.replace('__path__', encodedPath) : `/storage/${encodedPath}`;
    };

    const toast = (() => {
        const el  = $('#toast');
        const msg = $('#toastMsg');
        let timer;
        return (text, ms = 3000) => {
            if (!el) return;
            msg.textContent = text;
            el.hidden = false;
            clearTimeout(timer);
            timer = setTimeout(() => { el.hidden = true; }, ms);
        };
    })();

    /* ─── Fade-up observer ─── */
    const fadeObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); fadeObs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    $$('.fade-up').forEach(el => fadeObs.observe(el));

    /* ══════════════════════════════════════
       LIGHTBOX
    ══════════════════════════════════════ */
    const lightbox       = $('#lightbox');
    const lbImg          = $('#lightboxImg');
    const lbTitle        = $('#lightboxTitle');
    const lbDesc         = $('#lightboxDesc');
    const lbDate         = $('#lightboxDate');
    const lbLikeBtn      = $('#lbLikeBtn');
    const lbLikeCount    = $('#lbLikeCount');
    const lbCommentCount = $('#lbCommentCount');
    const lbComments     = $('#lbComments');
    const lbGalleryId    = $('#lbGalleryId');
    const lbCommentForm  = $('#lbCommentForm');
    const lbKomentar     = $('#lbKomentarInput');

    // Collect all card ids in DOM order for prev/next nav
    let allIds   = [];
    let currentIdx = 0;

    const collectIds = () => { allIds = $$('.photo-card[data-id]').map(c => c.dataset.id); };
    collectIds();

    const openLightbox = async (id) => {
        currentIdx = allIds.indexOf(String(id));
        await loadLightboxData(id);
        lightbox.hidden = false;
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        updateNavBtns();
    };

    const closeLightbox = () => {
        lightbox.hidden = true;
        lightbox.classList.remove('is-open');
        document.body.style.overflow = '';
    };

    const loadLightboxData = async (id) => {
        // Ambil data dari kartu yang sudah ada di DOM
        const card = $(`.photo-card[data-id="${id}"]`);
        if (!card) return;

        const imgSrc   = card.querySelector('.photo-card__img')?.src || '';
        const title    = card.querySelector('.photo-card__title')?.textContent || '';
        const desc     = card.querySelector('.photo-card__desc')?.textContent || '';
        const date     = card.querySelector('.meta-date')?.textContent?.trim() || '';
        const likeCount = card.querySelector('.like-count')?.textContent?.trim() || '0';
        const commentCount = card.querySelector('.action-btn--comment span')?.textContent?.trim() || '0';
        const isLiked  = card.querySelector('.action-btn--like')?.classList.contains('is-liked') || false;

        // Populate lightbox
        lbImg.classList.add('is-loading');
        lbImg.onload = () => lbImg.classList.remove('is-loading');
        lbImg.src    = imgSrc;
        lbImg.alt    = title;
        lbTitle.textContent     = title;
        lbDesc.textContent      = desc;
        lbDate.textContent      = date;
        lbLikeCount.textContent = likeCount;
        lbCommentCount.textContent = commentCount;
        if (lbGalleryId) lbGalleryId.value = id;

        // Like state sync
        if (lbLikeBtn) {
            lbLikeBtn.dataset.galleryId = id;
            lbLikeBtn.dataset.liked     = String(isLiked);
            syncLbLikeBtn(isLiked);
        }

        // Load komentar
        await loadKomentar(id);
    };

    const syncLbLikeBtn = (liked) => {
        if (!lbLikeBtn) return;
        const svg = lbLikeBtn.querySelector('svg');
        lbLikeBtn.classList.toggle('is-liked', liked);
        if (svg) svg.setAttribute('fill', liked ? 'currentColor' : 'none');
    };

    /* Komentar loader */
    const loadKomentar = async (id) => {
        if (!lbComments) return;
        lbComments.innerHTML = '<div class="lb-comments-loading"><div class="spinner"></div></div>';

        try {
            const res  = await fetch(url(ROUTES.komentar, id), { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            renderKomentar(data.data || data);
            lbCommentCount.textContent = (data.data || data).length;
        } catch {
            lbComments.innerHTML = '<p class="comments-empty">Gagal memuat komentar.</p>';
        }
    };

    const renderKomentar = (list) => {
        if (!lbComments) return;
        if (!list.length) {
            lbComments.innerHTML = '<p class="comments-empty">Belum ada komentar. Jadilah yang pertama!</p>';
            return;
        }
        lbComments.innerHTML = list.map(k => `
            <div class="comment-item">
                <div class="comment-avatar">${escHtml(k.user?.name?.charAt(0).toUpperCase() || '?')}</div>
                <div class="comment-body">
                    <div class="comment-name">${escHtml(k.user?.name || 'Pengguna')}</div>
                    <div class="comment-text">${escHtml(k.isi_komentar)}</div>
                    <div class="comment-time">${formatTime(k.created_at)}</div>
                </div>
            </div>
        `).join('');
    };

    const appendKomentar = (k) => {
        const empty = lbComments?.querySelector('.comments-empty');
        if (empty) empty.remove();

        const div = document.createElement('div');
        div.className = 'comment-item';
        div.innerHTML = `
            <div class="comment-avatar">${escHtml(k.user?.name?.charAt(0).toUpperCase() || '?')}</div>
            <div class="comment-body">
                <div class="comment-name">${escHtml(k.user?.name || 'Pengguna')}</div>
                <div class="comment-text">${escHtml(k.isi_komentar)}</div>
                <div class="comment-time">Baru saja</div>
            </div>
        `;
        lbComments?.appendChild(div);
        div.scrollIntoView({ behavior: 'smooth' });
    };

    /* Prev / Next */
    const updateNavBtns = () => {
        const prev = $('#lbPrev');
        const next = $('#lbNext');
        if (prev) prev.disabled = currentIdx <= 0;
        if (next) next.disabled = currentIdx >= allIds.length - 1;
    };

    const navigate = async (dir) => {
        const newIdx = currentIdx + dir;
        if (newIdx < 0 || newIdx >= allIds.length) return;
        currentIdx = newIdx;
        await loadLightboxData(allIds[currentIdx]);
        updateNavBtns();
    };

    /* Event: open lightbox via card */
    document.addEventListener('click', e => {
        const btn = e.target.closest('.photo-card__view-btn');
        if (btn) { e.preventDefault(); openLightbox(btn.dataset.id); }
    });

    /* Event: close */
    $('#lightboxClose')?.addEventListener('click', closeLightbox);
    $('#lightboxCloseBtn')?.addEventListener('click', closeLightbox);
    $('#lbPrev')?.addEventListener('click', () => navigate(-1));
    $('#lbNext')?.addEventListener('click', () => navigate(1));

    /* Keyboard */
    document.addEventListener('keydown', e => {
        if (!lightbox || lightbox.hidden) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft')  navigate(-1);
        if (e.key === 'ArrowRight') navigate(1);
    });

    /* ══════════════════════════════════════
       LIKE — AJAX
    ══════════════════════════════════════ */
    const toggleLike = async (galleryId, cardBtn, lbBtn) => {
        if (!CFG.isAuth) { window.location.href = ROUTES.index; return; }

        const liked = cardBtn?.dataset.liked === 'true' || lbBtn?.dataset.liked === 'true';

        try {
            const res  = await fetch(url(ROUTES.like, galleryId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ _method: liked ? 'DELETE' : 'POST' })
            });
            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Gagal');

            const newLiked = data.liked;
            const count    = data.total_like;

            // Update card
            if (cardBtn) {
                cardBtn.dataset.liked = String(newLiked);
                cardBtn.classList.toggle('is-liked', newLiked);
                const svg = cardBtn.querySelector('svg');
                if (svg) svg.setAttribute('fill', newLiked ? 'currentColor' : 'none');
                const span = cardBtn.querySelector('.like-count, span');
                if (span) span.textContent = count;
            }

            // Update lightbox
            if (lbBtn) {
                lbBtn.dataset.liked = String(newLiked);
                syncLbLikeBtn(newLiked);
                if (lbLikeCount) lbLikeCount.textContent = count;
            }

        } catch (err) {
            toast('Gagal: ' + err.message);
        }
    };

    /* Delegasi like di card */
    document.addEventListener('click', e => {
        const cardBtn = e.target.closest('.action-btn--like');
        if (!cardBtn || !cardBtn.dataset.galleryId) return;
        e.preventDefault();
        const id  = cardBtn.dataset.galleryId;
        const lbB = (lbLikeBtn?.dataset.galleryId === id) ? lbLikeBtn : null;
        toggleLike(id, cardBtn, lbB);
    });

    /* Like di lightbox */
    lbLikeBtn?.addEventListener('click', () => {
        const id = lbLikeBtn.dataset.galleryId;
        if (!id) return;
        const cardBtn = $(`.photo-card[data-id="${id}"] .action-btn--like`);
        toggleLike(id, cardBtn, lbLikeBtn);
    });

    /* ══════════════════════════════════════
       KOMENTAR — AJAX
    ══════════════════════════════════════ */
    lbCommentForm?.addEventListener('submit', async e => {
        e.preventDefault();
        const id   = lbGalleryId?.value;
        const isi  = lbKomentar?.value.trim();
        if (!id || !isi) return;

        const sendBtn = lbCommentForm.querySelector('.lbform__send');
        if (sendBtn) sendBtn.disabled = true;

        try {
            const res  = await fetch(url(ROUTES.komentar, id), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ gallery_id: id, isi_komentar: isi })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal mengirim komentar');

            // Append
            appendKomentar(data.komentar || data);
            lbKomentar.value = '';

            // Update counter di card
            const card = $(`.photo-card[data-id="${id}"]`);
            const commentSpan = card?.querySelector('.action-btn--comment span');
            if (commentSpan) commentSpan.textContent = parseInt(commentSpan.textContent || 0) + 1;
            if (lbCommentCount) lbCommentCount.textContent = parseInt(lbCommentCount.textContent || 0) + 1;

        } catch (err) {
            toast('Gagal: ' + err.message);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
        }
    });

    /* ══════════════════════════════════════
       UPLOAD FOTO
    ══════════════════════════════════════ */
    const uploadModal    = $('#uploadModal');
    const uploadForm     = $('#uploadForm');
    const uploadModalTitle = $('#uploadModalTitle');
    const dropzone       = $('#dropzone');
    const dropzoneInner  = $('#dropzoneInner');
    const dropzoneText   = $('#dropzoneText');
    const dropzoneHint   = $('#dropzoneHint');
    const dropzonePreview = $('#dropzonePreview');
    const fotoInput      = $('#fotoInput');
    const titleInput     = $('#judulFoto');
    const descTextarea   = $('#deskripsiFoto');
    const descCount      = $('#descCount');
    const uploadError    = $('#uploadError');
    const uploadSubmit   = $('#uploadSubmit');
    const uploadSubmitText = $('#uploadSubmitText');
    const uploadLoadingText = $('#uploadLoadingText');
    const uploadCancel   = $('#uploadCancel');
    let editingGalleryId = null;

    const setDropzonePreview = (src = '') => {
        if (!dropzonePreview || !dropzoneInner) return;

        if (src) {
            dropzonePreview.src = src;
            dropzonePreview.hidden = false;
            dropzoneInner.style.display = 'none';
            dropzone?.classList.add('has-preview');
            return;
        }

        dropzonePreview.src = '';
        dropzonePreview.hidden = true;
        dropzoneInner.style.display = '';
        dropzone?.classList.remove('has-preview');
    };

    const setUploadMode = (mode, card = null) => {
        if (!uploadForm) return;

        const isEdit = mode === 'edit';
        uploadForm.dataset.mode = isEdit ? 'edit' : 'create';
        editingGalleryId = isEdit ? card?.dataset.id : null;

        uploadModalTitle.textContent = isEdit ? 'Edit Foto Galeri' : 'Upload Foto Baru';
        uploadSubmitText.textContent = isEdit ? 'Simpan Perubahan' : 'Upload Foto';
        uploadLoadingText.textContent = isEdit ? 'Menyimpan...' : 'Mengupload...';
        dropzoneText.innerHTML = isEdit
            ? 'Ganti foto dengan <label for="fotoInput" class="dropzone__link">pilih file</label>'
            : 'Seret foto ke sini atau <label for="fotoInput" class="dropzone__link">pilih file</label>';
        dropzoneHint.textContent = isEdit
            ? 'Kosongkan jika tidak ingin mengganti gambar'
            : 'PNG, JPG, WebP · Maks 5 MB';

        if (fotoInput) {
            fotoInput.value = '';
            fotoInput.required = !isEdit;
        }

        if (titleInput) {
            titleInput.value = isEdit ? card?.dataset.title ?? '' : '';
        }

        if (descTextarea) {
            descTextarea.value = isEdit ? card?.dataset.description ?? '' : '';
            descCount.textContent = `${descTextarea.value.length} / 500`;
        }

        if (uploadError) uploadError.hidden = true;
        setDropzonePreview(isEdit ? card?.dataset.imageUrl ?? '' : '');
    };

    const openUpload = () => {
        if (!uploadModal) return;
        setUploadMode('create');
        uploadModal.hidden = false;
        document.body.style.overflow = 'hidden';
    };
    const openEdit = (card) => {
        if (!uploadModal || !card) return;
        setUploadMode('edit', card);
        uploadModal.hidden = false;
        document.body.style.overflow = 'hidden';
    };
    const closeUpload = () => {
        if (!uploadModal) return;
        uploadModal.hidden = true;
        document.body.style.overflow = '';
        setUploadMode('create');
    };

    $('#openUploadModal')?.addEventListener('click', openUpload);
    $('#openUploadModalEmpty')?.addEventListener('click', openUpload);
    $('#uploadClose')?.addEventListener('click', closeUpload);
    $('#uploadCloseBtn')?.addEventListener('click', closeUpload);
    uploadCancel?.addEventListener('click', closeUpload);

    /* Char counter */
    descTextarea?.addEventListener('input', () => {
        if (descCount) descCount.textContent = `${descTextarea.value.length} / 500`;
    });

    /* Dropzone file preview */
    const handleFile = (file) => {
        if (!file || !file.type.startsWith('image/')) { toast('Hanya file gambar yang diizinkan.'); return; }
        if (file.size > 5 * 1024 * 1024) { toast('Ukuran file maksimal 5 MB.'); return; }

        const reader = new FileReader();
        reader.onload = (ev) => {
            if (dropzonePreview) {
                dropzonePreview.src    = ev.target.result;
                dropzonePreview.hidden = false;
            }
            if (dropzoneInner) dropzoneInner.style.display = 'none';
            dropzone?.classList.add('has-preview');
        };
        reader.readAsDataURL(file);

        // Assign ke input agar ikut form
        const dt = new DataTransfer();
        dt.items.add(file);
        if (fotoInput) fotoInput.files = dt.files;
    };

    fotoInput?.addEventListener('change', () => { if (fotoInput.files[0]) handleFile(fotoInput.files[0]); });

    dropzone?.addEventListener('click', e => {
        if (!e.target.closest('label') && !e.target.closest('input')) fotoInput?.click();
    });
    dropzone?.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('is-dragover'); });
    dropzone?.addEventListener('dragleave', () => dropzone.classList.remove('is-dragover'));
    dropzone?.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('is-dragover');
        const file = e.dataTransfer.files[0];
        handleFile(file);
    });

    /* Submit upload */
    uploadForm?.addEventListener('submit', async e => {
        e.preventDefault();
        const isEdit = uploadForm.dataset.mode === 'edit';
        if (!isEdit && !fotoInput?.files[0]) { showUploadError('Pilih foto terlebih dahulu.'); return; }
        if (isEdit && !editingGalleryId) { showUploadError('Data foto yang diedit tidak ditemukan.'); return; }

        const btnText    = uploadSubmit?.querySelector('.btn-text');
        const btnLoading = uploadSubmit?.querySelector('.btn-loading');
        if (uploadSubmit) uploadSubmit.disabled = true;
        if (btnText)    btnText.hidden    = true;
        if (btnLoading) btnLoading.hidden = false;
        if (uploadError) uploadError.hidden = true;

        try {
            const formData = new FormData(uploadForm);
            const endpoint = isEdit ? url(ROUTES.update, editingGalleryId) : ROUTES.store;

            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            const res  = await fetch(endpoint, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(', ') || 'Upload gagal');

            const gallery = data.gallery || data;
            if (isEdit) {
                updateCard(gallery);
                toast('Foto berhasil diperbarui.');
            } else {
                prependCard(gallery);
                toast('Foto berhasil diupload.');
            }

            closeUpload();
        } catch (err) {
            showUploadError(err.message);
        } finally {
            if (uploadSubmit) uploadSubmit.disabled = false;
            if (btnText)    btnText.hidden    = false;
            if (btnLoading) btnLoading.hidden = true;
        }
    });

    const showUploadError = (msg) => {
        if (!uploadError) return;
        uploadError.textContent = msg;
        uploadError.hidden = false;
    };

    document.addEventListener('click', e => {
        const editBtn = e.target.closest('[data-gallery-edit]');
        if (!editBtn) return;

        e.preventDefault();
        e.stopPropagation();
        openEdit(editBtn.closest('.photo-card'));
    });

    document.addEventListener('click', async e => {
        const deleteBtn = e.target.closest('[data-gallery-delete]');
        if (!deleteBtn) return;

        e.preventDefault();
        e.stopPropagation();

        const galleryId = deleteBtn.dataset.galleryDelete;
        if (!galleryId || !window.confirm('Hapus foto ini dari galeri?')) return;

        deleteBtn.disabled = true;

        try {
            const res = await fetch(url(ROUTES.destroy, galleryId), {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json().catch(() => ({}));

            if (!res.ok) throw new Error(data.message || 'Foto gagal dihapus');

            const card = $(`.photo-card[data-id="${galleryId}"]`);
            card?.remove();
            collectIds();

            if (lbGalleryId?.value === String(galleryId)) {
                closeLightbox();
            }

            toast('Foto berhasil dihapus.');
        } catch (err) {
            deleteBtn.disabled = false;
            toast('Gagal: ' + err.message);
        }
    });

    const adminActionsHtml = (id) => {
        if (!CFG.canUpload) return '';

        return `
            <button class="action-btn action-btn--admin action-btn--edit" data-gallery-edit="${id}" type="button" aria-label="Edit foto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                <span>Edit</span>
            </button>
            <button class="action-btn action-btn--admin action-btn--delete" data-gallery-delete="${id}" type="button" aria-label="Hapus foto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                <span>Hapus</span>
            </button>
        `;
    };

    const updateCard = (foto) => {
        if (!foto?.id) return;

        const card = $(`.photo-card[data-id="${foto.id}"]`);
        if (!card) return;

        const imgSrc = normalizeImageUrl(foto.image_url || foto.gambar_url);
        card.dataset.title = foto.judul_foto || '';
        card.dataset.description = foto.deskripsi || '';
        card.dataset.imageUrl = imgSrc;

        const img = card.querySelector('.photo-card__img');
        if (img) {
            img.src = imgSrc;
            img.alt = foto.judul_foto || 'Foto galeri';
        }

        const title = card.querySelector('.photo-card__title');
        if (title) title.textContent = foto.judul_foto || '';

        const oldDesc = card.querySelector('.photo-card__desc');
        if (foto.deskripsi) {
            if (oldDesc) {
                oldDesc.textContent = foto.deskripsi.slice(0, 80);
            } else {
                const desc = document.createElement('p');
                desc.className = 'photo-card__desc';
                desc.textContent = foto.deskripsi.slice(0, 80);
                title?.after(desc);
            }
        } else {
            oldDesc?.remove();
        }

        const share = card.querySelector('.action-btn--share');
        if (share) {
            share.dataset.title = foto.judul_foto || 'Foto Batu Kuda';
        }

        if (lbGalleryId?.value === String(foto.id)) {
            if (lbImg) {
                lbImg.src = imgSrc;
                lbImg.alt = foto.judul_foto || 'Foto galeri';
            }
            if (lbTitle) lbTitle.textContent = foto.judul_foto || '';
            if (lbDesc) lbDesc.textContent = foto.deskripsi || '';
        }
    };

    /* Prepend new card to grid */
    const prependCard = (foto) => {
        const grid = $('#photoGrid');
        if (!foto) return;
        if (!grid) {
            window.location.reload();
            return;
        }

        const imgSrc = normalizeImageUrl(foto.image_url || foto.gambar_url);

        const article = document.createElement('article');
        article.className = 'photo-card fade-up visible';
        article.dataset.id = foto.id;
        article.dataset.title = foto.judul_foto || '';
        article.dataset.description = foto.deskripsi || '';
        article.dataset.imageUrl = imgSrc;
        article.innerHTML = `
            <div class="photo-card__img-wrap">
                <img src="${escHtml(imgSrc)}" alt="${escHtml(foto.judul_foto)}" loading="lazy" class="photo-card__img">
                <div class="photo-card__overlay">
                    <button class="photo-card__view-btn" data-id="${foto.id}" aria-label="Lihat detail">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </div>
            </div>
            <div class="photo-card__body">
                <h3 class="photo-card__title">${escHtml(foto.judul_foto)}</h3>
                ${foto.deskripsi ? `<p class="photo-card__desc">${escHtml(foto.deskripsi.slice(0, 80))}</p>` : ''}
                <div class="photo-card__meta">
                    <span class="meta-date">Baru saja</span>
                </div>
                <div class="photo-card__actions">
                    <button class="action-btn action-btn--like" data-gallery-id="${foto.id}" data-liked="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <span class="like-count">0</span>
                    </button>
                    <button class="action-btn action-btn--comment photo-card__view-btn" data-id="${foto.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>0</span>
                    </button>
                    ${adminActionsHtml(foto.id)}
                    <button class="action-btn action-btn--share" data-url="${window.location.origin}/gallery/${foto.id}" data-title="${escHtml(foto.judul_foto)}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    </button>
                </div>
            </div>
        `;
        grid.prepend(article);
        collectIds();
    };

    /* ══════════════════════════════════════
       SHARE
    ══════════════════════════════════════ */
    document.addEventListener('click', async e => {
        const btn = e.target.closest('.action-btn--share');
        if (!btn) return;
        e.stopPropagation();
        const shareUrl   = btn.dataset.url || window.location.href;
        const shareTitle = btn.dataset.title || 'Foto Batu Kuda';

        if (navigator.share) {
            try {
                await navigator.share({ title: shareTitle, url: shareUrl });
            } catch { /* user cancel */ }
        } else {
            await navigator.clipboard?.writeText(shareUrl);
            toast('Link disalin ke clipboard! 📋');
        }
    });

    /* ══════════════════════════════════════
       SEARCH (debounce + HTMX-free AJAX)
    ══════════════════════════════════════ */
    const searchInput = $('#gallerySearch');
    const clearBtn    = $('#clearSearch');
    let   searchTimer;

    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const q = searchInput.value.trim();
        if (clearBtn) clearBtn.hidden = !q;

        searchTimer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);
            if (q) params.set('q', q); else params.delete('q');
            params.delete('page');
            window.location.search = params.toString();
        }, 600);
    });

    clearBtn?.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.hidden   = true;
        const params = new URLSearchParams(window.location.search);
        params.delete('q');
        window.location.search = params.toString();
    });

    /* ══════════════════════════════════════
       UTILS
    ══════════════════════════════════════ */
    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function normalizeImageUrl(path) {
        if (!path) return '';
        const value = String(path);
        if (/^(https?:)?\/\//.test(value)) return value;
        if (value.startsWith('/')) return value;
        if (value.startsWith('storage/')) return imageRoute(value.replace(/^storage\//, ''));
        if (value.startsWith('gallery/')) return imageRoute(value);
        return `/${value}`;
    }

    function formatTime(isoStr) {
        if (!isoStr) return '';
        try {
            const d = new Date(isoStr);
            const diff = (Date.now() - d) / 1000;
            if (diff < 60)   return 'Baru saja';
            if (diff < 3600) return `${Math.floor(diff/60)} menit lalu`;
            if (diff < 86400)return `${Math.floor(diff/3600)} jam lalu`;
            return d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
        } catch { return isoStr; }
    }
});
