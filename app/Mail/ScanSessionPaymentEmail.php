<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScanSessionPaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $scanSession;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($scanSession)
    {
        $this->scanSession = $scanSession;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.scan_session_payment')
                    ->text('emails.plain.scan_session_payment')
                    ->subject($this->scanSession->description());
    }
}
