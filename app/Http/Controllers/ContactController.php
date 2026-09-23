<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __construct()
    {
        // Backstop behind the captcha: caps how fast a single IP can submit the contact form,
        // across all three locale routes (they all reach this same action). Named (3rd arg) so
        // it doesn't share a bucket with unrelated `throttle:` uses elsewhere in the app.
        $this->middleware('throttle:10,1,contact')->only('store');
    }

    public function show()
    {
        $locale  = $this->detectLocale();
        $seoData = config("seo.{$locale}.contact", []);
        return view('app.pages.contact.index', ['locale' => $locale, 'seoPage' => 'contact', 'seoData' => $seoData]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
            'captcha' => 'required|captcha',
        ], [
            // English only, matching the other messages on this form (none of which are localized).
            'captcha.required' => 'Please enter the code shown in the image.',
            'captcha.captcha' => 'That code was incorrect or has expired. Please try again with the new image.',
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

    private function detectLocale(): string
    {
        $seg = request()->segment(1);
        return in_array($seg, ['es', 'fr'], true) ? $seg : 'en';
    }
}
