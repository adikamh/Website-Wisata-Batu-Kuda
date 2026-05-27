/**
 * Xendit Payment Integration
 * Handles payment creation and submission flow
 */

class XenditPayment {
    constructor(options = {}) {
        this.formSelector = options.formSelector || '.ticket-form';
        this.buttonSelector = options.buttonSelector || '.ticket-submit';
        this.csrfTokenSelector = options.csrfTokenSelector || 'meta[name="csrf-token"]';
        this.apiEndpoint = options.apiEndpoint || '/xendit/create-payment';
        this.onSuccess = options.onSuccess || null;
        this.onError = options.onError || null;
    }

    init() {
        const form = document.querySelector(this.formSelector);
        const button = document.querySelector(this.buttonSelector);

        if (!form || !button) {
            console.warn('XenditPayment: Form or button not found');
            return;
        }

        // Prevent default form submission
        form.addEventListener('submit', (e) => e.preventDefault());

        // Handle button click
        button.addEventListener('click', () => this.handlePayment(form));
    }

    async handlePayment(form) {
        try {
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get button
            const button = document.querySelector(this.buttonSelector);
            const originalText = button.textContent;

            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Memproses pembayaran...';

            // Step 1: Submit form to create transaction
            const formData = new FormData(form);
            const createTxResponse = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!createTxResponse.ok) {
                const error = await createTxResponse.json();
                throw new Error(error.message || 'Gagal membuat transaksi');
            }

            const txData = await createTxResponse.json();

            if (!txData.transaction_id) {
                throw new Error('Transaksi berhasil dibuat tapi ID tidak ditemukan');
            }

            // Step 2: Create payment with Xendit
            const csrfToken = document.querySelector(this.csrfTokenSelector)?.content;

            const paymentResponse = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    transaction_id: txData.transaction_id,
                    customer_email: txData.email || form.querySelector('input[name="email"]')?.value,
                    customer_name: txData.name || form.querySelector('input[name="name"]')?.value
                })
            });

            if (!paymentResponse.ok) {
                const error = await paymentResponse.json();
                throw new Error(error.message || 'Gagal membuat invoice pembayaran');
            }

            const paymentData = await paymentResponse.json();

            if (!paymentData.success) {
                throw new Error(paymentData.message || 'Pembuatan invoice gagal');
            }

            // Step 3: Success callback or redirect
            if (this.onSuccess) {
                this.onSuccess(paymentData);
            } else {
                // Default: redirect to Xendit invoice
                window.location.href = paymentData.redirect_url;
            }

        } catch (error) {
            console.error('XenditPayment Error:', error);

            // Error callback
            if (this.onError) {
                this.onError(error);
            } else {
                // Default: show error alert
                this.showError(error.message);
            }
        } finally {
            // Re-enable button
            const button = document.querySelector(this.buttonSelector);
            button.disabled = false;
            button.textContent = originalText || 'Pesan Tiket Sekarang';
        }
    }

    async checkPaymentStatus(externalId) {
        try {
            const response = await fetch(`/xendit/check-status?external_id=${externalId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengecek status pembayaran');
            }

            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Check Status Error:', error);
            throw error;
        }
    }

    showError(message) {
        // Try to use Swal if available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message
            });
        } else {
            alert(`Error: ${message}`);
        }
    }

    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message
            });
        } else {
            alert(`Success: ${message}`);
        }
    }
}

// Auto-init when DOM ready
document.addEventListener('DOMContentLoaded', function() {
    const payment = new XenditPayment();
    payment.init();

    // Expose to window for manual usage
    window.XenditPayment = XenditPayment;
});

// Export for ES6 modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = XenditPayment;
}
