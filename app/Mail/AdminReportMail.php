<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReportMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $subjectText,
        public readonly string $bodyText,
        private readonly string $filename,
        private readonly string $content,
        private readonly string $mime,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject($this->subjectText)
            ->text('emails.admin-report')
            ->attachData($this->content, $this->filename, [
                'mime' => $this->mime,
            ]);
    }
}
