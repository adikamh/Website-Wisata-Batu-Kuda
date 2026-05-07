document.addEventListener('DOMContentLoaded', () => {
    const ticketPage = document.querySelector('.ticket-page');

    if (!ticketPage) {
        return;
    }

    const packageRadios = ticketPage.querySelectorAll('input[name="ticket_category_id"]');
    const paymentCategoryRadios = ticketPage.querySelectorAll('input[name="payment_category"]');
    const paymentGroups = ticketPage.querySelectorAll('[data-payment-group]');
    const visitDateInput = ticketPage.querySelector('input[name="visit_date"]');
    const campingEndDateInput = ticketPage.querySelector('input[name="camping_end_date"]');
    const visitorCountInput = ticketPage.querySelector('input[name="visitor_count"]');
    const summaryBox = ticketPage.querySelector('[data-ticket-summary]');
    const form = ticketPage.querySelector('.ticket-form');
    const submitButton = ticketPage.querySelector('.ticket-submit');

    const formatRupiah = (value) => new Intl.NumberFormat('id-ID').format(value);
    const getSelectedPackage = () => ticketPage.querySelector('input[name="ticket_category_id"]:checked');
    const getSelectedPaymentCategory = () => ticketPage.querySelector('input[name="payment_category"]:checked')?.value ?? 'bank';

    const syncCheckoutDate = () => {
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
        if (!visitDateInput?.value || !campingEndDateInput?.value) {
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
        const packageName = selectedPackage?.dataset.ticketName ?? '-';
        const packagePrice = Number.parseInt(selectedPackage?.dataset.ticketPrice ?? '0', 10);
        const total = packagePrice * visitors * days;

        summaryBox.querySelector('[data-summary-package]').textContent = packageName;
        summaryBox.querySelector('[data-summary-visitors]').textContent = String(visitors);
        summaryBox.querySelector('[data-summary-days]').textContent = String(days);
        summaryBox.querySelector('[data-summary-price]').textContent = formatRupiah(packagePrice);
        summaryBox.querySelector('[data-summary-total]').textContent = formatRupiah(total);
    };

    packageRadios.forEach((radio) => {
        radio.addEventListener('change', () => {
            syncCheckoutDate();
            syncSummary();
        });
    });

    paymentCategoryRadios.forEach((radio) => {
        radio.addEventListener('change', syncPaymentGroups);
    });

    visitDateInput?.addEventListener('change', () => {
        syncCheckoutDate();
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

    syncCheckoutDate();
    syncPaymentGroups();
    syncSummary();
});
