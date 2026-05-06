document.addEventListener('DOMContentLoaded', () => {
    const ticketPage = document.querySelector('.ticket-page');

    if (!ticketPage) {
        return;
    }

    const packageRadios = ticketPage.querySelectorAll('input[name="package_type"]');
    const paymentCategoryRadios = ticketPage.querySelectorAll('input[name="payment_category"]');
    const paymentGroups = ticketPage.querySelectorAll('[data-payment-group]');
    const campingField = ticketPage.querySelector('.ticket-field--camping');
    const visitDateInput = ticketPage.querySelector('input[name="visit_date"]');
    const campingEndDateInput = ticketPage.querySelector('input[name="camping_end_date"]');
    const visitorCountInput = ticketPage.querySelector('input[name="visitor_count"]');
    const summaryBox = ticketPage.querySelector('[data-ticket-summary]');
    const form = ticketPage.querySelector('.ticket-form');
    const submitButton = ticketPage.querySelector('.ticket-submit');

    const formatRupiah = (value) => new Intl.NumberFormat('id-ID').format(value);
    const getSelectedPackage = () => ticketPage.querySelector('input[name="package_type"]:checked')?.value ?? 'visit';
    const getSelectedPaymentCategory = () => ticketPage.querySelector('input[name="payment_category"]:checked')?.value ?? 'bank';
    const toDatasetKey = (packageName, suffix) => {
        const normalized = packageName.charAt(0).toUpperCase() + packageName.slice(1);
        return `package${normalized}${suffix}`;
    };

    const syncCampingField = () => {
        const selectedPackage = getSelectedPackage();
        const isCamping = selectedPackage === 'camping';

        campingField?.classList.toggle('is-hidden', !isCamping);

        if (!visitDateInput || !campingEndDateInput) {
            return;
        }

        campingEndDateInput.min = visitDateInput.value || '';

        if (!campingEndDateInput.value || campingEndDateInput.value < campingEndDateInput.min) {
            campingEndDateInput.value = campingEndDateInput.min;
        }
    };

    const syncPaymentGroups = () => {
        const selectedCategory = getSelectedPaymentCategory();

        paymentGroups.forEach((group) => {
            const isActive = group.dataset.paymentGroup === selectedCategory;
            group.classList.toggle('is-active', isActive);

            if (isActive && !group.querySelector('input[name="payment_method"]:checked')) {
                group.querySelector('input[name="payment_method"]')?.click();
            }
        });
    };

    const calculateDays = () => {
        if (getSelectedPackage() !== 'camping' || !visitDateInput?.value || !campingEndDateInput?.value) {
            return 1;
        }

        const start = new Date(`${visitDateInput.value}T00:00:00`);
        const end = new Date(`${campingEndDateInput.value}T00:00:00`);
        const duration = end.getTime() - start.getTime();

        if (Number.isNaN(duration) || duration < 0) {
            return 1;
        }

        return Math.floor(duration / 86400000) + 1;
    };

    const syncSummary = () => {
        if (!summaryBox) {
            return;
        }

        const selectedPackage = getSelectedPackage();
        const visitors = Math.max(1, Number.parseInt(visitorCountInput?.value ?? '1', 10) || 1);
        const days = calculateDays();
        const packageName = summaryBox.dataset[toDatasetKey(selectedPackage, 'Name')] ?? '-';
        const packagePrice = Number.parseInt(summaryBox.dataset[toDatasetKey(selectedPackage, 'Price')] ?? '0', 10);
        const total = packagePrice * visitors * days;

        summaryBox.querySelector('[data-summary-package]').textContent = packageName;
        summaryBox.querySelector('[data-summary-visitors]').textContent = String(visitors);
        summaryBox.querySelector('[data-summary-days]').textContent = String(days);
        summaryBox.querySelector('[data-summary-price]').textContent = formatRupiah(packagePrice);
        summaryBox.querySelector('[data-summary-total]').textContent = formatRupiah(total);
    };

    packageRadios.forEach((radio) => {
        radio.addEventListener('change', () => {
            syncCampingField();
            syncSummary();
        });
    });

    paymentCategoryRadios.forEach((radio) => {
        radio.addEventListener('change', syncPaymentGroups);
    });

    visitDateInput?.addEventListener('change', () => {
        syncCampingField();
        syncSummary();
    });
    campingEndDateInput?.addEventListener('change', syncSummary);
    visitorCountInput?.addEventListener('input', syncSummary);

    form?.addEventListener('submit', () => {
        if (!form.checkValidity() || !submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Memproses pesanan...';
    });

    syncCampingField();
    syncPaymentGroups();
    syncSummary();
});
