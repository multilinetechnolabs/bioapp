<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScanSessionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $scan_session;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($scan_session)
    {
        $this->scan_session = $scan_session;
    }

    public function send(\Illuminate\Contracts\Mail\Mailer $mailer)
    {
        parent::send($mailer);

        foreach ($this->attachments as $attachment) {
            if (\File::exists($attachment['file'])) {
                \File::delete($attachment['file']);
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename = '';
        $filePath = '';
        $fileMimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        try {
            $filename = $this->scan_session->export();
            $filePath = storage_path().'/app/'. $filename;
        } catch (\Throwable $e) {
            \Log::error('Unable to export scan session attachment.', [
                'scan_session_id' => $this->scan_session->id,
                'exception' => $e,
            ]);
        }

        $mail = $this->view('emails.scan_session')
                    ->text('emails.plain.scan_session')
                    ->subject(env('APP_TITLE').' | Scan Session')
                    ->with([ 'content' => '']);

        if (! empty($filename) && ! empty($filePath) && \File::exists($filePath)) {
            $mail->attach($filePath, [ 'as' => $filename, 'mime' => $fileMimeType ]);
        }

        return $mail;
    }
}
