<?php

namespace App\Mail;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserTicketMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Transaction $transaction,
        public readonly TransactionDetail $detail,
        public readonly string $ticketCode,
        public readonly array $rentalItems,
        public readonly string $packageName,
        public readonly ?string $pdfContent = null,
        public readonly ?string $pdfFilename = null,
        public readonly string $pdfMime = 'application/pdf'
    ) {
    }

    public function build(): self
    {
        $email = $this
            ->subject('Tiket Batu Kuda - ' . $this->ticketCode)
            ->view('emails.ticket')
            ->with([
                'transaction' => $this->transaction,
                'detail' => $this->detail,
                'ticketCode' => $this->ticketCode,
                'rentalItems' => $this->rentalItems,
                'packageName' => $this->packageName,
            ]);

        if (! empty($this->pdfContent) && ! empty($this->pdfFilename)) {
            $email->attachData($this->pdfContent, $this->pdfFilename, [
                'mime' => $this->pdfMime,
            ]);
        }

        return $email;
    }
}
