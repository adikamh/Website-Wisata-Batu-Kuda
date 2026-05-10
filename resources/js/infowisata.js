// ─── info-wisata.js ────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. Navbar scroll ───────────────────────────────────
    const navbar = document.getElementById('navbar');
    const tabs   = document.getElementById('iwTabs');

    window.addEventListener('scroll', () => {
        const sy = window.scrollY;
        navbar?.classList.toggle('scrolled', sy > 50);
        tabs?.classList.toggle('shadowed', sy > 200);
    }, { passive: true });

    // ── 2. Reveal on scroll ────────────────────────────────
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // ── 3. Category filter tabs ────────────────────────────
    document.querySelectorAll('.iw-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.iw-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            document.querySelectorAll('.iw-section').forEach((sec, i) => {
                const match = filter === 'all' || sec.dataset.kategori === filter;
                sec.style.display = match ? 'block' : 'none';
                if (match) {
                    sec.style.setProperty('--delay', `${i * 0.06}s`);
                    sec.classList.remove('visible');
                    setTimeout(() => observer.observe(sec), 10);
                }
            });
        });
    });

    // ── 4. Dropdown user menu ──────────────────────────────
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

    // ── 5. Flash auto-dismiss ──────────────────────────────
    const flash = document.getElementById('flashMsg');
    if (flash) setTimeout(() => flash?.remove(), 5000);

    // ── 6. Textarea char count ─────────────────────────────
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

// ─── Modal: Create / Edit Seksi ────────────────────────────
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

    // Hide poin modal
    if (poinM) poinM.style.display = 'none';
    modal.style.display = '';

    if (mode === 'create') {
        form.action     = '/info-wisata';
        methSpan.innerHTML = '';
        title.textContent  = 'Tambah Seksi Info';
        icon.textContent   = '✨';
        sub.textContent    = 'Buat konten informasi baru untuk wisatawan';
        saveText.textContent = 'Simpan Seksi';
        form.reset();
        document.getElementById('descCount').textContent = '0';
    } else {
        // Edit: fetch existing data dari section di DOM
        const sec = document.querySelector(`.iw-section[data-id="${sectionId}"]`);
        if (!sec) return;

        form.action       = `/info-wisata/${sectionId}`;
        methSpan.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        title.textContent  = 'Edit Seksi Info';
        icon.textContent   = '✏️';
        sub.textContent    = 'Perbarui konten informasi';
        saveText.textContent = 'Simpan Perubahan';

        // Populate fields from DOM
        const kat   = sec.querySelector('.iw-sec-kat')?.textContent?.trim() || '';
        const ico   = sec.querySelector('.iw-sec-icon')?.textContent?.trim() || '';
        const jdl   = sec.querySelector('.iw-sec-title')?.textContent?.trim() || '';
        const desc  = sec.querySelector('.iw-sec-desc')?.textContent?.trim() || '';

        document.getElementById('fKategori').value = kat;
        document.getElementById('fIcon').value     = ico;
        document.getElementById('fJudul').value    = jdl;
        document.getElementById('fDeskripsi').value = desc;
        document.getElementById('descCount').textContent = desc.length;
    }

    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// ─── Modal: Create / Edit Poin ──────────────────────────────
window.openPoinModal = function(mode, sectionId, poinIndex = null, poinData = null) {
    const overlay    = document.getElementById('modalOverlay');
    const sectionM   = document.getElementById('sectionModal');
    const poinModal  = document.getElementById('poinModal');
    const form       = document.getElementById('poinForm');
    const methSpan   = document.getElementById('poinMethodField');
    const titleEl    = document.getElementById('poinModalTitle');

    sectionM.style.display = 'none';
    poinModal.style.display = '';

    if (mode === 'create') {
        form.action        = `/info-wisata/${sectionId}/poin`;
        methSpan.innerHTML = '';
        titleEl.textContent = 'Tambah Poin';
        form.reset();
    } else {
        form.action        = `/info-wisata/${sectionId}/poin/${poinIndex}`;
        methSpan.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        titleEl.textContent = 'Edit Poin';
        document.getElementById('fPoinJudul').value = poinData?.judul || '';
        document.getElementById('fPoinIsi').value   = poinData?.isi   || '';
    }

    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// ─── Close all modals ───────────────────────────────────────
window.closeAllModals = function() {
    document.getElementById('modalOverlay')?.classList.remove('active');
    document.body.style.overflow = '';
};

// ─── Delete confirm ─────────────────────────────────────────
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

// Close confirm on overlay click
document.getElementById('confirmOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});

// ESC to close
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeAllModals();
        document.getElementById('confirmOverlay')?.classList.remove('active');
    }
});