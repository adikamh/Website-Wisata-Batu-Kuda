/**
 * Xendit Payment Integration
 * Handles payment creation and submission flow
 */

class XenditPayment {
    constructor(options = {}) {
        this.formSelector = options.formSelector || '.ticket-form';
        this.buttonSelector = options.buttonSelector || '#ticketSubmitBtn';
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
        const button = document.querySelector(this.buttonSelector);
        const originalText = button?.textContent || 'Pesan Tiket Sekarang';

        try {
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!button) {
                throw new Error('Tombol pembayaran tidak ditemukan. Refresh halaman dan coba lagi.');
            }

            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Memproses pembayaran...';

            // Step 1: Submit form to create transaction
            const formData = new FormData(form);
            const createTxResponse = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const responseText = await createTxResponse.text();
            console.log('Transaction response status:', createTxResponse.status);
            console.log('Transaction response:', responseText);

            let txData;
            try {
                txData = JSON.parse(responseText);
            } catch (e) {
                throw new Error(`Backend error (${createTxResponse.status}): ${responseText.substring(0, 200)}`);
            }

            if (!createTxResponse.ok) {
                throw new Error(txData.message || `Error: ${txData.errors || 'Unknown error'}`);
            }

            if (!txData.transaction_id) {
                throw new Error('Transaksi berhasil dibuat tapi ID tidak ditemukan');
            }

            // Step 2: Create payment with Xendit
            const csrfToken = document.querySelector(this.csrfTokenSelector)?.content;

            if (!csrfToken) {
                throw new Error('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
            }

            const paymentResponse = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    transaction_id: txData.transaction_id,
                    customer_email: txData.email || form.querySelector('input[name="email"]')?.value,
                    customer_name: txData.name || form.querySelector('input[name="name"]')?.value
                })
            });

            const paymentText = await paymentResponse.text();
            console.log('Payment response status:', paymentResponse.status);
            console.log('Payment response:', paymentText);

            let paymentData;
            try {
                paymentData = JSON.parse(paymentText);
            } catch (e) {
                throw new Error(`Server error (${paymentResponse.status}): ${paymentText.substring(0, 300)}`);
            }

            if (!paymentResponse.ok) {
                throw new Error(paymentData.message || `Server error: ${paymentData.error_code || paymentResponse.status}`);
            }

            if (!paymentData.success) {
                throw new Error(paymentData.message || 'Pembuatan invoice gagal');
            }

            const redirectUrl = paymentData.redirect_url || paymentData.invoice_url;

            if (!redirectUrl) {
                throw new Error('Tidak ada URL invoice yang diterima dari server');
            }

            // Step 3: Success callback or redirect
            if (this.onSuccess) {
                this.onSuccess(paymentData);
            } else {
                // Default: redirect to Xendit invoice
                window.location.href = redirectUrl;
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

            if (button) {
                button.disabled = false;
                button.textContent = originalText;
            }
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
