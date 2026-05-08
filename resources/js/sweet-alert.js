import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const baseOptions = {
    confirmButtonColor: '#2d6a4f',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
    focusConfirm: false,
    customClass: {
        popup: 'batu-kuda-swal',
        confirmButton: 'batu-kuda-swal__confirm',
        cancelButton: 'batu-kuda-swal__cancel',
    },
};

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3200,
    timerProgressBar: true,
});

const fire = (options = {}) => Swal.fire({
    ...baseOptions,
    ...options,
});

const notify = (icon, message, options = {}) => fire({
    icon,
    title: options.title ?? defaultTitle(icon),
    text: message,
    ...options,
});

const showToast = (message, icon = 'success', options = {}) => toast.fire({
    icon,
    title: message,
    ...options,
});

const confirm = (options = {}) => fire({
    icon: 'warning',
    title: 'Apakah Anda yakin?',
    text: 'Aksi ini akan diproses.',
    showCancelButton: true,
    confirmButtonText: 'Ya, lanjutkan',
    cancelButtonText: 'Batal',
    ...options,
});

const defaultTitle = (icon) => ({
    success: 'Berhasil',
    error: 'Gagal',
    warning: 'Perhatian',
    info: 'Informasi',
    question: 'Konfirmasi',
}[icon] ?? 'Informasi');

const normalizePayload = (payload) => {
    if (!payload) {
        return [];
    }

    return Array.isArray(payload) ? payload : [payload];
};

const showPayloadAlerts = (payload) => {
    const alerts = normalizePayload(payload);

    return alerts.reduce((chain, alert) => (
        chain.then(() => fire({
            icon: alert.icon ?? 'info',
            title: alert.title ?? defaultTitle(alert.icon ?? 'info'),
            text: alert.text,
            html: alert.html,
            timer: alert.timer,
            showConfirmButton: alert.showConfirmButton ?? true,
            confirmButtonText: alert.confirmButtonText ?? 'OK',
            ...alert.options,
        }))
    ), Promise.resolve());
};

const readComponentPayloads = () => {
    document
        .querySelectorAll('[data-sweet-alert-payload]:not([data-sweet-alert-loaded])')
        .forEach((element) => {
            element.dataset.sweetAlertLoaded = 'true';

            try {
                showPayloadAlerts(JSON.parse(element.textContent || '[]'));
            } catch {
                notify('error', 'Konfigurasi alert tidak valid.');
            }
        });
};

const bindConfirmTriggers = () => {
    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-swal-confirm]');

        if (!trigger || trigger.dataset.swalConfirmed === 'true') {
            return;
        }

        event.preventDefault();

        const result = await confirm({
            title: trigger.dataset.swalTitle || 'Apakah Anda yakin?',
            text: trigger.dataset.swalText || 'Aksi ini akan diproses.',
            icon: trigger.dataset.swalIcon || 'warning',
            confirmButtonText: trigger.dataset.swalConfirmText || 'Ya, lanjutkan',
            cancelButtonText: trigger.dataset.swalCancelText || 'Batal',
        });

        if (!result.isConfirmed) {
            return;
        }

        trigger.dataset.swalConfirmed = 'true';

        const form = trigger.closest('form');

        if (form) {
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit(trigger);
            } else {
                form.submit();
            }
            return;
        }

        if (trigger.href) {
            window.location.href = trigger.href;
        }
    });
};

window.Swal = Swal;
window.BatuKudaAlert = {
    swal: Swal,
    fire,
    toast: showToast,
    confirm,
    success: (message, options = {}) => notify('success', message, options),
    error: (message, options = {}) => notify('error', message, options),
    warning: (message, options = {}) => notify('warning', message, options),
    info: (message, options = {}) => notify('info', message, options),
    fromPayload: showPayloadAlerts,
};
window.AppAlert = window.BatuKudaAlert;

document.addEventListener('batu-kuda:alert', (event) => {
    showPayloadAlerts(event.detail);
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        readComponentPayloads();
        bindConfirmTriggers();
    });
} else {
    readComponentPayloads();
    bindConfirmTriggers();
}
