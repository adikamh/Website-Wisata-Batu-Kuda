<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            \Xendit\Xendit::setApiKey($secretKey);
        } catch (\Exception $e) {
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

            $transaction = Transaction::findOrFail($validated['transaction_id']);

            // Cek apakah invoice sudah dibuat
            if ($transaction->xendit_invoice_id) {
                Log::info('Invoice already exists for transaction', ['id' => $transaction->id]);
                return response()->json([
                    'success' => true,
                    'invoice_url' => $transaction->xendit_invoice_url,
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

            // Create invoice parameters (simplified)
            $invoiceParams = [
                'external_id' => $externalId,
                'amount' => (int) $transaction->total_bayar,
                'payer_email' => $validated['customer_email'],
                'description' => 'Pembelian Tiket Wisata Batu Kuda',
            ];

            // Create invoice
            $invoice = \Xendit\Invoice::create($invoiceParams);

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

        } catch (\Exception $e) {
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

            // Handle invoice notification
            if (isset($payload['event']) && $payload['event'] === 'invoice.paid') {
                return $this->handleInvoicePaid($payload);
            }

            // Handle expired invoice
            if (isset($payload['event']) && $payload['event'] === 'invoice.expired') {
                return $this->handleInvoiceExpired($payload);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
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

            Log::info('Payment marked as success', [
                'transaction_id' => $transaction->id,
                'invoice_id' => $payload['data']['id'] ?? null,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
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

        } catch (\Exception $e) {
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
            // Jika tidak ada token di config, skip verifikasi (optional)
            return true;
        }

        return hash_equals($xIncomingCallbackTokenHeader ?? '', $expectedToken);
    }

    /**
     * Success redirect (opsional)
     */
    public function success(Request $request)
    {
        return redirect('/')->with('success', 'Pembayaran berhasil! Invoice URL: ' . ($request->get('invoice_url') ?? ''));
    }

    /**
     * Failed redirect (opsional)
     */
    public function failed(Request $request)
    {
        return redirect('/')->with('error', 'Pembayaran gagal. Silakan coba lagi.');
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

            $invoice = \Xendit\Invoice::retrieve([
                'external_id' => $validated['external_id'],
            ]);

            return response()->json([
                'success' => true,
                'status' => $invoice['status'] ?? 'pending',
                'data' => $invoice,
            ]);

        } catch (\Exception $e) {
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
