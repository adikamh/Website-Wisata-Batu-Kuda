document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const tabs   = document.getElementById('iwTabs');

    window.addEventListener('scroll', () => {
        const sy = window.scrollY;
        navbar?.classList.toggle('scrolled', sy > 50);
        tabs?.classList.toggle('shadowed', sy > 200);
    }, { passive: true });
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.06, rootMargin: '0px 0px -32px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    document.querySelectorAll('.iw-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.iw-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            document.querySelectorAll('.iw-section').forEach((sec, i) => {
                const match = filter === 'all' || sec.dataset.kategori === filter;
                sec.style.display = match ? '' : 'none';
                if (match) {
                    sec.style.setProperty('--delay', `${i * 0.06}s`);
                    sec.classList.remove('visible');
                    setTimeout(() => observer.observe(sec), 10);
                }
            });

            // Re-build sidebar for visible items
            rebuildSidebar(filter);
        });
    });

    // ── Sidebar: build & active tracking ─────────────────
    function rebuildSidebar(filter) {
        const list = document.getElementById('sidebarNavList');
        if (!list) return;
        list.querySelectorAll('.iw-nav-item').forEach(item => {
            const sid  = item.dataset.sid;
            const sec  = document.querySelector(`.iw-section[data-id="${sid}"]`);
            const kat  = sec?.dataset.kategori || '';
            item.style.display = (filter === 'all' || kat === filter) ? '' : 'none';
        });
    }

    // Active sidebar item on scroll
    const sidebarItems = document.querySelectorAll('.iw-nav-item[data-sid]');
    const sections     = document.querySelectorAll('.iw-section[id^="sec-"]');

    if (sidebarItems.length && sections.length) {
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id.replace('sec-', '');
                    sidebarItems.forEach(item => item.classList.remove('active'));
                    const active = document.querySelector(`.iw-nav-item[data-sid="${id}"]`);
                    active?.classList.add('active');
                }
            });
        }, { rootMargin: '-20% 0px -60% 0px', threshold: 0 });

        sections.forEach(sec => sectionObserver.observe(sec));
    }

    // ── User dropdown ──────────────────────────────────────
    const userBtn  = document.getElementById('userMenuBtn');
    const dropdown = document.getElementById('userDropdown');
    if (userBtn && dropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = dropdown.classList.toggle('open');
            userBtn.classList.toggle('active', open);
            userBtn.setAttribute('aria-expanded', open);
        });
        document.addEventListener('click', () => {
            dropdown.classList.remove('open');
            userBtn?.classList.remove('active');
        });
    }

    // ── Flash auto-remove ──────────────────────────────────
    const flash = document.getElementById('flashMsg');
    if (flash) setTimeout(() => flash?.remove(), 5000);

    // ── Textarea char count ────────────────────────────────
    const descTA = document.getElementById('fDeskripsi');
    const descCt = document.getElementById('descCount');
    if (descTA && descCt) {
        const updateCount = () => {
            descCt.textContent = descTA.value.length;
            descCt.style.color = descTA.value.length > 550 ? '#e05252' : '';
        };
        descTA.addEventListener('input', updateCount);
    }
});

window.carouselScroll = function(trackId, dir) {
    const track = document.getElementById(trackId);
    if (!track) return;
    const itemWidth = track.querySelector('.iw-gal-item')?.offsetWidth || 220;
    track.scrollBy({ left: dir * (itemWidth + 12), behavior: 'smooth' });
};

window.openModal = function(mode, sectionId = null) {
    const overlay  = document.getElementById('modalOverlay');
    const modal    = document.getElementById('sectionModal');
    const poinM    = document.getElementById('poinModal');
    const form     = document.getElementById('sectionForm');
    const methSpan = document.getElementById('methodField');
    const title    = document.getElementById('modalTitle');
    const icon     = document.getElementById('modalIcon');
    const sub      = document.getElementById('modalSub');
    const saveText = document.getElementById('saveBtnText');

    if (poinM) poinM.style.display = 'none';
    modal.style.display = '';

    if (mode === 'create') {
        form.action          = '/infowisata';
        methSpan.innerHTML   = '';
        title.textContent    = 'Tambah Seksi Info';
        icon.textContent     = '✨';
        sub.textContent      = 'Buat konten informasi baru untuk wisatawan';
        saveText.textContent = 'Simpan Seksi';
        form.reset();
        document.getElementById('descCount').textContent = '0';
    } else {
        const sec = document.querySelector(`.iw-section[data-id="${sectionId}"]`);
        if (!sec) return;

        form.action        = `/infowisata/${sectionId}`;
        methSpan.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        title.textContent  = 'Edit Seksi Info';
        icon.textContent   = '✏️';
        sub.textContent    = 'Perbarui konten informasi';
        saveText.textContent = 'Simpan Perubahan';

        const kat   = sec.querySelector('.iw-sec-kat')?.textContent?.trim()  || '';
        const ico   = sec.querySelector('.iw-sec-icon')?.textContent?.trim() || '';
        const jdl   = sec.querySelector('.iw-sec-title')?.textContent?.trim()|| '';
        const desc  = sec.querySelector('.iw-sec-desc')?.textContent?.trim() || '';

        document.getElementById('fKategori').value  = kat;
        document.getElementById('fIcon').value      = ico;
        document.getElementById('fJudul').value     = jdl;
        document.getElementById('fDeskripsi').value = desc;
        document.getElementById('descCount').textContent = desc.length;
    }

    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
};

window.openPoinModal = function(mode, sectionId, poinIndex = null, poinData = null) {
    const overlay    = document.getElementById('modalOverlay');
    const sectionM   = document.getElementById('sectionModal');
    const poinModal  = document.getElementById('poinModal');
    const form       = document.getElementById('poinForm');
    const methodField = document.getElementById('poinMethodField');
    const titleEl    = document.getElementById('poinModalTitle');

    sectionM.style.display = 'none';
    poinModal.style.display = '';

    if (mode === 'create') {
        form.action = `/infowisata/${sectionId}/poin`;
        methodField.value = '';
        titleEl.textContent = 'Tambah Poin';
        form.reset();
    } else {
        form.action = `/infowisata/${sectionId}/poin/${poinIndex}`;
        methodField.value = 'PUT';
        titleEl.textContent = 'Edit Poin';
        document.getElementById('fPoinJudul').value = poinData?.judul || '';
        document.getElementById('fPoinIsi').value   = poinData?.isi   || '';
    }

    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
};

window.closeAllModals = function() {
    document.getElementById('modalOverlay')?.classList.remove('active');
    document.body.style.overflow = '';
};

window.confirmDelete = function(btn) {
    const form    = btn.closest('form');
    const overlay = document.getElementById('confirmOverlay');
    const confBtn = document.getElementById('confirmDeleteBtn');

    overlay.classList.add('active');
    confBtn.onclick = () => {
        form.submit();
        overlay.classList.remove('active');
    };
};

document.getElementById('confirmOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeAllModals();
        document.getElementById('confirmOverlay')?.classList.remove('active');
    }
});