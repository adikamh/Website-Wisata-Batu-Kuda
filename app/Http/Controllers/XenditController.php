<?php

namespace App\Http\Controllers;

use App\Mail\UserTicketMail;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class XenditController extends Controller
{
    /**
     * Inisialisasi Xendit API
     */
    private function initXendit()
    {
        $secretKey = config('services.xendit.secret_key');
        
        if (!$secretKey) {
            throw new \Exception('XENDIT_SECRET_KEY not configured in .env');
        }

        Log::info('Initializing Xendit with key prefix', [
            'key_prefix' => substr($secretKey, 0, 20) . '...'
        ]);

        try {
            \Xendit\Configuration::setXenditKey($secretKey);
        } catch (\Throwable $e) {
            Log::error('Xendit initialization failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Buat invoice pembayaran di Xendit
     */
    public function createPayment(Request $request)
    {
        try {
            // Log request
            Log::info('Xendit createPayment request', $request->all());

            $this->initXendit();

            // Validasi request
            $validated = $request->validate([
                'transaction_id' => 'required|exists:transactions,id',
                'customer_email' => 'required|email',
                'customer_name' => 'required|string',
            ]);

            $transaction = Transaction::query()
                ->whereKey($validated['transaction_id'])
                ->when(! Auth::user()?->isAdmin(), fn ($query) => $query->where('user_id', Auth::id()))
                ->firstOrFail();

            // Cek apakah invoice sudah dibuat
            if ($transaction->xendit_invoice_id) {
                Log::info('Invoice already exists for transaction', ['id' => $transaction->id]);
                return response()->json([
                    'success' => true,
                    'invoice_url' => $transaction->xendit_invoice_url,
                    'redirect_url' => $transaction->xendit_invoice_url,
                    'message' => 'Invoice sudah dibuat sebelumnya',
                ]);
            }

            // Generate external ID unik
            $externalId = 'order-' . $transaction->id . '-' . time();

            Log::info('Creating invoice', [
                'external_id' => $externalId,
                'amount' => (int) $transaction->total_bayar,
                'email' => $validated['customer_email'],
            ]);

            // Create invoice request
            $invoiceRequest = new \Xendit\Invoice\CreateInvoiceRequest();
            $invoiceRequest->setExternalId($externalId);
            $invoiceRequest->setAmount((float) $transaction->total_bayar);
            $invoiceRequest->setPayerEmail($validated['customer_email']);
            $invoiceRequest->setDescription('Pembelian Tiket Wisata Batu Kuda');
            $invoiceRequest->setSuccessRedirectUrl(route('xendit.success', [
                'transaction_id' => $transaction->id,
                'external_id' => $externalId,
            ]));
            $invoiceRequest->setFailureRedirectUrl(route('xendit.failed', [
                'transaction_id' => $transaction->id,
            ]));

            // Create invoice
            $invoiceApi = new \Xendit\Invoice\InvoiceApi();
            $invoice = $invoiceApi->createInvoice($invoiceRequest);

            // Simpan ke database
            $transaction->update([
                'xendit_invoice_id' => $invoice['id'],
                'xendit_external_id' => $externalId,
                'xendit_invoice_url' => $invoice['invoice_url'],
                'xendit_response' => json_encode($invoice),
                'payment_method' => 'xendit',
            ]);

            Log::info('Xendit invoice created', [
                'transaction_id' => $transaction->id,
                'invoice_id' => $invoice['id'],
            ]);

            return response()->json([
                'success' => true,
                'invoice_url' => $invoice['invoice_url'],
                'invoice_id' => $invoice['id'],
                'redirect_url' => $invoice['invoice_url'],
            ]);

        } catch (\Throwable $e) {
            Log::error('Xendit payment creation failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return better error response
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
                'error_code' => $e->getCode(),
                'debug_class' => config('app.debug') ? get_class($e) : null,
            ], 500);
        }
    }

    /**
     * Handle webhook dari Xendit
     */
    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Xendit webhook received', $payload);

            // Verifikasi signature
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Invalid webhook signature');
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid signature'
                ], 401);
            }

            // Determine Xendit event type
            $eventType = $payload['event'] ?? $payload['type'] ?? null;

            // Handle invoice notification
            if ($eventType === 'invoice.paid') {
                return $this->handleInvoicePaid($payload);
            }

            // Handle expired invoice
            if ($eventType === 'invoice.expired') {
                return $this->handleInvoiceExpired($payload);
            }

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Handle pembayaran berhasil
     */
    private function handleInvoicePaid($payload)
    {
        try {
            $external_id = $payload['data']['external_id'] ?? null;

            if (!$external_id) {
                Log::warning('No external_id in webhook payload');
                return response()->json(['success' => false], 400);
            }

            // Cari transaction berdasarkan external_id
            $transaction = Transaction::where('xendit_external_id', $external_id)->first();

            if (!$transaction) {
                Log::warning('Transaction not found', ['external_id' => $external_id]);
                return response()->json(['success' => false], 404);
            }

            // Update transaction status
            $transaction->update([
                'status_pembayaran' => 'success',
                'xendit_response' => json_encode($payload),
            ]);

            $this->sendTicketEmailIfNeeded($transaction->fresh());

            Log::info('Payment marked as success', [
                'transaction_id' => $transaction->id,
                'invoice_id' => $payload['data']['id'] ?? null,
            ]);

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('Error handling paid invoice', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Handle pembayaran expired
     */
    private function handleInvoiceExpired($payload)
    {
        try {
            $external_id = $payload['data']['external_id'] ?? null;

            if (!$external_id) {
                return response()->json(['success' => false], 400);
            }

            $transaction = Transaction::where('xendit_external_id', $external_id)->first();

            if (!$transaction) {
                return response()->json(['success' => false], 404);
            }

            // Update transaction status
            $transaction->update([
                'status_pembayaran' => 'expired',
                'xendit_response' => json_encode($payload),
            ]);

            Log::info('Payment marked as expired', [
                'transaction_id' => $transaction->id,
            ]);

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('Error handling expired invoice', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Verifikasi webhook signature
     */
    private function verifyWebhookSignature(Request $request)
    {
        $xIncomingCallbackTokenHeader = $request->header('X-Callback-Token');
        $expectedToken = config('services.xendit.callback_token');

        if (!$expectedToken) {
            Log::error('XENDIT_CALLBACK_TOKEN is not configured; webhook rejected.');
            return false;
        }

        return hash_equals($xIncomingCallbackTokenHeader ?? '', $expectedToken);
    }

    /**
     * Success redirect (opsional)
     */
    public function success(Request $request)
    {
        $transaction = $this->resolveRedirectTransaction($request);

        if (! $transaction) {
            return redirect()
                ->route('tiket')
                ->with('warning', 'Pembayaran diproses oleh Xendit, tetapi transaksi tidak ditemukan di sistem.');
        }

        try {
            if ($transaction->status_pembayaran !== 'success') {
                $transaction->update([
                    'status_pembayaran' => 'success',
                ]);
            }

            $this->sendTicketEmailIfNeeded($transaction->fresh());

            return redirect()
                ->route('tiket')
                ->with('success', 'Payment berhasil. Tiket dikirim ke email, silakan cek email Anda.');
        } catch (\Throwable $e) {
            Log::error('Failed to finalize Xendit success redirect', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('tiket')
                ->with('warning', 'Payment berhasil, tetapi tiket belum berhasil dikirim. Silakan hubungi admin.');
        }
    }

    /**
     * Failed redirect (opsional)
     */
    public function failed(Request $request)
    {
        return redirect('/')->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    private function resolveRedirectTransaction(Request $request): ?Transaction
    {
        $query = Transaction::query();

        if ($request->filled('external_id')) {
            return $query
                ->where('xendit_external_id', $request->query('external_id'))
                ->first();
        }

        if ($request->filled('transaction_id')) {
            return $query
                ->whereKey($request->query('transaction_id'))
                ->first();
        }

        return null;
    }

    private function sendTicketEmailIfNeeded(?Transaction $transaction): void
    {
        if (! $transaction || $transaction->ticket_emailed_at) {
            return;
        }

        $transaction->loadMissing([
            'user',
            'details.tiketKategori',
            'details.eTicket',
            'rentalItems',
        ]);

        $detail = $transaction->details->first();
        $user = $transaction->user;

        if (! $detail || blank($user?->email)) {
            Log::warning('Ticket email skipped because transaction detail or user email is missing', [
                'transaction_id' => $transaction->id,
            ]);

            return;
        }

        $ticketCode = $detail->eTicket?->ticket_code
            ?? 'BK-' . now()->format('YmdHis') . '-' . $transaction->id;

        $rentalItems = $transaction->rentalItems
            ->map(fn ($item) => [
                'name' => $item->facility_name,
                'quantity' => (int) $item->quantity,
                'price' => (int) $item->price,
                'subtotal' => (int) $item->subtotal,
            ])
            ->values()
            ->all();

        $packageName = $detail->tiketKategori?->nama_kategori ?? 'Tiket Batu Kuda';

        $ktpPaperSize = [0, 0, 86 * 2.8346456693, 54 * 2.8346456693];

        $pdfContent = Pdf::loadView('pdf.ticket_ktp', [
            'transaction' => $transaction,
            'detail' => $detail,
            'ticketCode' => $ticketCode,
            'rentalItems' => $rentalItems,
            'packageName' => $packageName,
            'user' => $user,
        ])->setPaper($ktpPaperSize)->output();

        Mail::to($user->email)->send(new UserTicketMail(
            transaction: $transaction,
            detail: $detail,
            ticketCode: $ticketCode,
            rentalItems: $rentalItems,
            packageName: $packageName,
            pdfContent: $pdfContent,
            pdfFilename: 'tiket-' . $ticketCode . '.pdf'
        ));

        $transaction->forceFill([
            'ticket_emailed_at' => now(),
        ])->save();

        Log::info('Ticket PDF email sent after Xendit payment success', [
            'transaction_id' => $transaction->id,
            'ticket_code' => $ticketCode,
            'email' => $user->email,
        ]);
    }

    /**
     * Cek status pembayaran
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $this->initXendit();

            $validated = $request->validate([
                'external_id' => 'required|string',
            ]);

            $transaction = Transaction::where('xendit_external_id', $validated['external_id'])->first();

            if (! $transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi Xendit tidak ditemukan',
                ], 404);
            }

            if (! $transaction->xendit_invoice_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice Xendit belum dibuat untuk transaksi ini',
                ], 404);
            }

            $invoiceApi = new \Xendit\Invoice\InvoiceApi();
            $invoice = $invoiceApi->getInvoiceById($transaction->xendit_invoice_id);

            return response()->json([
                'success' => true,
                'status' => $invoice['status'] ?? 'pending',
                'data' => $invoice,
            ]);

        } catch (\Throwable $e) {
            Log::error('Failed to check payment status', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
