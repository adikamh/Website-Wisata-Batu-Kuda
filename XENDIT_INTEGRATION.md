# Integrasi Xendit - Panduan Implementasi

## 1. Persiapan
Akun Xendit sudah dibuat (sandbox)
API Keys sudah ditambahkan ke `.env`
SDK Xendit sudah diinstal
Migration untuk kolom Xendit sudah dibuat
Controller, routes, dan config sudah siap

## 2. Update `.env` dengan Keys Anda

Buka file `.env` dan ganti:
```
XENDIT_SECRET_KEY=your_actual_xendit_secret_key
XENDIT_PUBLIC_KEY=your_actual_xendit_public_key
XENDIT_CALLBACK_TOKEN=your_webhook_callback_token_optional
```

Dapatkan keys dari: https://dashboard.xendit.co/settings/developers

## 3. Flow Pembayaran

### Server Side Flow:
1. User submit form pemesanan tiket → backend create Transaction record
2. Frontend call POST `/xendit/create-payment` dengan transaction_id
3. Backend create invoice di Xendit API
4. Xendit return invoice_url
5. Frontend redirect user ke invoice_url (Xendit hosted page)
6. User bayar di Xendit page
7. Xendit send webhook ke `/xendit/webhook` (backend)
8. Backend verify signature dan update transaction status
9. Frontend poll status atau listen webhook untuk update

### Webhook URL Configuration:
Di Xendit Dashboard → Settings → Webhook:
- **Invoice Paid URL**: `https://yourwebsite.com/xendit/webhook`
- **Invoice Expired URL**: `https://yourwebsite.com/xendit/webhook`
- **Callback Token**: (opsional, untuk verifikasi signature)

## 4. Implementasi Frontend

### Opsi 1: Redirect ke Xendit Hosted Page (RECOMMENDED)

Di file `resources/views/layout/tiket.blade.php`, tambah ID pada submit button:
```html
<button type="button" id="ticketSubmitBtn" class="btn-primary ticket-submit">
    Pesan Tiket Sekarang
</button>
```

Buat file `resources/js/xendit-payment.js`:
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('ticketSubmitBtn');
    const ticketForm = document.querySelector('.ticket-form');

    if (!submitBtn || !ticketForm) return;

    submitBtn.addEventListener('click', async function(e) {
        e.preventDefault();

        // Validate form first
        if (!ticketForm.checkValidity()) {
            ticketForm.reportValidity();
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';

        try {
            // Step 1: Submit form to create transaction
            const formData = new FormData(ticketForm);
            const response = await fetch(ticketForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal membuat transaksi');
            }

            // Step 2: Create payment with Xendit
            const paymentResponse = await fetch('/xendit/create-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    transaction_id: data.transaction_id,
                    customer_email: data.email,
                    customer_name: data.name
                })
            });

            const paymentData = await paymentResponse.json();

            if (!paymentData.success) {
                throw new Error(paymentData.message || 'Gagal membuat invoice');
            }

            // Step 3: Redirect ke Xendit payment page
            window.location.href = paymentData.redirect_url;

        } catch (error) {
            console.error('Payment error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan saat membuat pembayaran'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Pesan Tiket Sekarang';
        }
    });
});
```

Include di `resources/views/layout/tiket.blade.php`:
```html
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/xendit-payment.js'])
```

### Opsi 2: Embedded Payment (Optional)

Gunakan Xendit JS library untuk payment form di halaman Anda sendiri:

```html
<script src="https://checkout.xendit.co/v1/xendit.min.js"></script>

<script>
document.getElementById('paymentBtn').addEventListener('click', function() {
    xendit.checkout({
        publicKey: '{{ config("services.xendit.public_key") }}',
        invoiceID: 'invoice_id_from_backend'
    });
});
</script>
```

## 5. Backend Controller Methods (Sudah Dibuat)

### POST `/xendit/create-payment`
Request body:
```json
{
    "transaction_id": 1,
    "customer_email": "customer@example.com",
    "customer_name": "John Doe"
}
```

Response:
```json
{
    "success": true,
    "invoice_url": "https://invoice.xendit.co/webpay/...",
    "invoice_id": "xyz123",
    "redirect_url": "https://invoice.xendit.co/webpay/..."
}
```

### GET `/xendit/check-status?external_id=order-1-123456`
Check status pembayaran setelah user kembali

Response:
```json
{
    "success": true,
    "status": "PAID|PENDING|EXPIRED",
    "data": { ...invoice_data }
}
```

### POST `/xendit/webhook` (Automated)
Xendit akan otomatis memanggil endpoint ini saat ada payment status change.

## 6. Database Schema (Sudah Ditambah)

Kolom baru di tabel `transactions`:
- `xendit_invoice_id` - Invoice ID dari Xendit
- `xendit_external_id` - Order ID yang kita kirim ke Xendit
- `xendit_invoice_url` - URL invoice untuk redirect
- `xendit_response` - Full JSON response dari Xendit

## 7. Testing di Sandbox

### Xendit Test Cards:
- **Visa Accepted**: 4000 0000 0000 0002
- **Mastercard Accepted**: 5105 1051 0510 5100
- **Visa Declined**: 4000 0000 0000 0069
- **Amount**: Bisa pakai angka apa saja
- **Expiry**: MM/YY apapun (masa depan)
- **CVV**: 123 (3 digit apapun)

### Testing Flow:
1. Login ke aplikasi
2. Beli tiket
3. Submit form → akan redirect ke Xendit invoice
4. Gunakan test card di atas untuk bayar
5. Setelah bayar, check database transaction status → harus `success`

## 8. Webhook Verification

Controller sudah implement `verifyWebhookSignature()`:
- Cek header `X-Callback-Token` dari request
- Bandingkan dengan `XENDIT_CALLBACK_TOKEN` di `.env`
- Gunakan `hash_equals()` untuk secure comparison

## 9. Error Handling

Sudah ditambahkan logging di:
- `storage/logs/laravel.log`
- Cek saat development: `php artisan logs:tail`

## 10. Migration dari Midtrans ke Xendit (Optional)

Jika sebelumnya pakai Midtrans, bisa support kedua-duanya:
- Cek field `payment_method` column
- Jika `payment_method = 'xendit'` → gunakan Xendit
- Jika `payment_method = 'midtrans'` → gunakan Midtrans logic lama

## 11. Production Deployment

### Sebelum deploy ke production:
1. Update `.env` dengan production keys Xendit
2. Pastikan HTTPS enabled
3. Webhook URL di Xendit dashboard: `https://production-domain.com/xendit/webhook`
4. Set `APP_ENV=production` dan `APP_DEBUG=false`
5. Test end-to-end dengan live keys (gunakan live payment account test)
6. Monitor logs untuk webhook delivery success

## 12. Troubleshooting

### Invoice tidak dibuat?
```bash
# Check logs
php artisan logs:tail

# Verify API key di .env
cat .env | grep XENDIT
```

### Webhook tidak diterima?
- Cek logs di Xendit Dashboard → Webhook History
- Pastikan URL benar dan HTTPS
- Verifikasi signature header setting

### Transaction tidak update setelah bayar?
- Verify webhook endpoint accessible
- Check X-Callback-Token jika digunakan
- Pastikan external_id match di request dan webhook

## 13. API Reference

Dokumentasi lengkap Xendit: https://xendit.readme.io/

Contoh API calls:
- Create Invoice: https://xendit.readme.io/reference/create-invoice
- Get Invoice: https://xendit.readme.io/reference/get-invoice-by-external-id
- Disbursement: https://xendit.readme.io/reference/create-disbursement
- Balance: https://xendit.readme.io/reference/get-balance

---

