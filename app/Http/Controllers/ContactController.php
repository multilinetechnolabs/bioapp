<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $emailSent = false;

        // Always store first — email is best-effort
        $contact = ContactMessage::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'subject'    => $data['subject'] ?? null,
            'message'    => $data['message'],
            'email_sent' => false,
        ]);

        try {
            $adminEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS'));
            $subject    = $data['subject'] ? 'Contact: ' . $data['subject'] : 'New Contact Message';
            $body       = "From: {$data['name']} <{$data['email']}>\n\n{$data['message']}";

            Mail::raw($body, function ($msg) use ($adminEmail, $subject, $data) {
                $msg->to($adminEmail)
                    ->replyTo($data['email'], $data['name'])
                    ->subject($subject);
            });

            $emailSent = true;
            $contact->update(['email_sent' => true]);
        } catch (\Throwable $e) {
            Log::error('Contact form email failed.', [
                'contact_message_id' => $contact->id,
                'exception'          => $e->getMessage(),
                'trace'             => $e->getTraceAsString(),
            ]);
        }

        return redirect()->back()
            ->with('contact.success', 'Your message has been received. We\'ll get back to you soon!');
    }
}
