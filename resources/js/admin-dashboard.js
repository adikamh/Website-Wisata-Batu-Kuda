/**
 * admin-dashboard.js
 * JS terpisah untuk Admin Dashboard Batu Kuda
 * Fitur: sidebar toggle (buka/tutup), sweet alert logout & delete,
 *        section navigation, modals (detail tiket, ticket CRUD)
 */

import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {

    /* =============================================
       1. SIDEBAR TOGGLE (Desktop)
    ============================================= */
    const sidebar       = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminMain     = document.getElementById('adminMain');
    const SIDEBAR_KEY   = 'bk_sidebar_collapsed';

    function setSidebarCollapsed(collapsed) {
        if (!sidebar) return;
        sidebar.classList.toggle('collapsed', collapsed);
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
        }
        try { localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0'); } catch (_) {}
    }

    // Restore state on load
    try {
        if (localStorage.getItem(SIDEBAR_KEY) === '1') {
            setSidebarCollapsed(true);
        }
    } catch (_) {}

    sidebarToggle?.addEventListener('click', function () {
        const isCollapsed = sidebar.classList.contains('collapsed');
        setSidebarCollapsed(!isCollapsed);
    });

    /* =============================================
       2. SIDEBAR MOBILE TOGGLE
    ============================================= */
    const mobileToggle   = document.getElementById('mobileSidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openMobileSidebar() {
        sidebar?.classList.add('mobile-open');
        sidebarOverlay?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        sidebar?.classList.remove('mobile-open');
        sidebarOverlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    mobileToggle?.addEventListener('click', openMobileSidebar);
    sidebarOverlay?.addEventListener('click', closeMobileSidebar);

    /* =============================================
       3. SECTION NAVIGATION (Kelola User / Tiket)
    ============================================= */
    const menuLinks = document.querySelectorAll('[data-admin-menu]');
    const sections  = document.querySelectorAll('[data-admin-section]');
    const pageTitle = document.getElementById('adminPageTitle');

    const sectionTitles = {
        'kelola-user': 'Kelola Pengguna',
        'tiket':       'Manajemen Tiket',
    };

    function showSection(sectionName) {
        sections.forEach(function (section) {
            section.classList.toggle('hidden', section.dataset.adminSection !== sectionName);
        });

        menuLinks.forEach(function (link) {
            const isActive = link.dataset.adminMenu === sectionName;
            link.classList.toggle('active', isActive);
        });

        if (pageTitle && sectionTitles[sectionName]) {
            pageTitle.textContent = sectionTitles[sectionName];
        }

        closeMobileSidebar();
    }

    menuLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const sectionName = link.dataset.adminMenu;
            showSection(sectionName);
            history.replaceState(null, '', '#' + sectionName);
        });
    });

    // Handle initial hash
    const initialHash = window.location.hash.replace('#', '');
    if (initialHash && document.querySelector('[data-admin-section="' + initialHash + '"]')) {
        showSection(initialHash);
    }

    /* =============================================
       4. SWEET ALERT — LOGOUT
    ============================================= */
    const logoutBtn  = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');

    logoutBtn?.addEventListener('click', function () {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Yakin ingin keluar dari panel admin?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-sign-out-alt mr-1"></i> Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then(function (result) {
            if (result.isConfirmed) {
                logoutForm?.submit();
            }
        });
    });

    /* =============================================
       5. SWEET ALERT — DELETE USER
    ============================================= */
    document.querySelectorAll('[data-delete-user]').forEach(function (button) {
        button.addEventListener('click', function () {
            const userId   = button.dataset.userId;
            const userName = button.dataset.userName ?? 'pengguna ini';

            Swal.fire({
                title: 'Hapus Pengguna?',
                html: 'Akun <strong>' + userName + '</strong> akan dihapus secara permanen dan tidak dapat dipulihkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteUserForm-' + userId);
                    form?.submit();
                }
            });
        });
    });

    /* =============================================
       6. SWEET ALERT — DELETE TIKET (CRUD)
    ============================================= */
    document.querySelectorAll('[data-delete-ticket]').forEach(function (button) {
        button.addEventListener('click', function () {
            const ticketId   = button.dataset.ticketId;
            const ticketName = button.dataset.ticketName ?? 'tiket ini';

            Swal.fire({
                title: 'Hapus Tiket?',
                html: 'Tiket <strong>' + ticketName + '</strong> akan dihapus dan tidak dapat dipulihkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteTicketForm-' + ticketId);
                    form?.submit();
                }
            });
        });
    });

    /* =============================================
       7. MODAL — CREATE TICKET
    ============================================= */
    const ticketCreateModal        = document.getElementById('ticketCreateModal');
    const openTicketCreateButtons  = document.querySelectorAll('[data-ticket-create-open]');
    const closeTicketCreateButtons = document.querySelectorAll('[data-ticket-create-close]');

    function openTicketCreateModal()  {
        ticketCreateModal?.classList.remove('hidden');
        ticketCreateModal?.classList.add('flex');
    }
    function closeTicketCreateModal() {
        ticketCreateModal?.classList.add('hidden');
        ticketCreateModal?.classList.remove('flex');
    }

    openTicketCreateButtons.forEach(btn => btn.addEventListener('click', openTicketCreateModal));
    closeTicketCreateButtons.forEach(btn => btn.addEventListener('click', closeTicketCreateModal));
    ticketCreateModal?.addEventListener('click', function (e) {
        if (e.target === ticketCreateModal) closeTicketCreateModal();
    });

    if (ticketCreateModal?.dataset.shouldOpen === 'true' && window.location.hash === '#tiket') {
        openTicketCreateModal();
    }

    /* =============================================
       8. MODAL — EDIT TICKET
    ============================================= */
    document.querySelectorAll('[data-ticket-edit-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = document.getElementById(button.dataset.target);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.querySelectorAll('[data-ticket-edit-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = button.closest('[id^="ticketEditModal-"]');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });

    document.querySelectorAll('[id^="ticketEditModal-"]').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });

    /* =============================================
       9. MODAL — DETAIL TIKET
    ============================================= */
    const ticketDetailModal        = document.getElementById('ticketDetailModal');
    const openTicketDetailButtons  = document.querySelectorAll('[data-ticket-detail-open]');
    const closeTicketDetailButtons = document.querySelectorAll('[data-ticket-detail-close]');
    const modalJumlahTiket  = document.getElementById('modalJumlahTiket');
    const modalTanggalMasuk = document.getElementById('modalTanggalMasuk');
    const modalTanggalKeluar= document.getElementById('modalTanggalKeluar');
    const modalNama  = document.getElementById('modalNama');
    const modalPaket = document.getElementById('modalPaket');

    function openTicketDetailModal(button) {
        if (!ticketDetailModal) return;
        if (modalJumlahTiket)   modalJumlahTiket.textContent   = button.dataset.jumlah  ?? '-';
        if (modalTanggalMasuk)  modalTanggalMasuk.textContent  = button.dataset.masuk   ?? '-';
        if (modalTanggalKeluar) modalTanggalKeluar.textContent = button.dataset.keluar  ?? '-';
        if (modalNama)          modalNama.textContent           = button.dataset.nama    ?? '-';
        if (modalPaket)         modalPaket.textContent          = button.dataset.paket   ?? '-';
        ticketDetailModal.classList.remove('hidden');
        ticketDetailModal.classList.add('flex');
    }

    function closeTicketDetailModal() {
        ticketDetailModal?.classList.add('hidden');
        ticketDetailModal?.classList.remove('flex');
    }

    openTicketDetailButtons.forEach(btn => btn.addEventListener('click', () => openTicketDetailModal(btn)));
    closeTicketDetailButtons.forEach(btn => btn.addEventListener('click', closeTicketDetailModal));
    ticketDetailModal?.addEventListener('click', function (e) {
        if (e.target === ticketDetailModal) closeTicketDetailModal();
    });

    /* =============================================
       10. CHART — Sales Analytics
    ============================================= */
    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas && typeof Chart !== 'undefined') {
        new Chart(salesCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: [1250000, 1420000, 1380000, 1650000, 1820000, 2100000, 1950000],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 12 } } },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0' },
                        ticks: { callback: v => 'Rp ' + (v / 1000) + 'rb' },
                        title: { display: true, text: 'Pendapatan', font: { size: 11 } },
                    },
                    x: {
                        grid: { display: false },
                        title: { display: true, text: 'Hari', font: { size: 11 } },
                    },
                },
            },
        });
    }

    /* =============================================
       11. PASSWORD TOGGLE (Halaman Create Admin)
    ============================================= */
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = btn.dataset.togglePassword;
            const input    = document.getElementById(targetId);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
            }
        });
    });
});