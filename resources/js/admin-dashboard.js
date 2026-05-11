document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('adminSidebar');
    const mainContent = document.getElementById('adminMain');
    const toggleButton = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('adminSidebarOverlay');
    const desktopQuery = window.matchMedia('(min-width: 1024px)');
    const storageKey = 'admin-sidebar-collapsed';

    const setOverlay = (visible) => {
        overlay?.classList.toggle('hidden', !visible);
        document.body.style.overflow = visible ? 'hidden' : '';
    };

    const setCollapsed = (collapsed) => {
        sidebar?.classList.toggle('collapsed', collapsed);
        mainContent?.classList.toggle('is-collapsed', collapsed);
        toggleButton?.setAttribute('aria-expanded', String(!collapsed));

        try {
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
        } catch (_) {
            // Local storage can be unavailable in private or restricted browsers.
        }
    };

    const closeMobileSidebar = () => {
        sidebar?.classList.remove('is-open');
        setOverlay(false);
    };

    const applyInitialSidebarState = () => {
        if (desktopQuery.matches) {
            closeMobileSidebar();
            let shouldCollapse = false;

            try {
                shouldCollapse = localStorage.getItem(storageKey) === '1';
            } catch (_) {
                shouldCollapse = false;
            }

            setCollapsed(shouldCollapse);
            return;
        }

        sidebar?.classList.remove('collapsed');
        mainContent?.classList.remove('is-collapsed');
        closeMobileSidebar();
    };

    toggleButton?.addEventListener('click', () => {
        if (desktopQuery.matches) {
            setCollapsed(!sidebar?.classList.contains('collapsed'));
            return;
        }

        const open = !sidebar?.classList.contains('is-open');
        sidebar?.classList.toggle('is-open', open);
        setOverlay(open);
    });

    overlay?.addEventListener('click', closeMobileSidebar);
    desktopQuery.addEventListener('change', applyInitialSidebarState);
    applyInitialSidebarState();

    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');

    const closeNotification = () => {
        notificationDropdown?.setAttribute('hidden', '');
        notificationToggle?.setAttribute('aria-expanded', 'false');
    };

    notificationToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = !notificationDropdown?.hasAttribute('hidden');
        notificationDropdown?.toggleAttribute('hidden', isOpen);
        notificationToggle.setAttribute('aria-expanded', String(!isOpen));
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.notification')) {
            closeNotification();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNotification();
            closeMobileSidebar();
        }
    });

    document.querySelectorAll('.sidebar-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (!desktopQuery.matches) {
                closeMobileSidebar();
            }
        });
    });
});
