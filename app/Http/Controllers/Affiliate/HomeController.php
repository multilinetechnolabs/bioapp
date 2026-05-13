<?php

namespace App\Http\Controllers\Affiliate;

use Illuminate\Http\Request;

use Mail;

use App\Models\Inquiry;

class HomeController extends BaseController
{
    public function index()
    {
        return view('affiliate.pages.index');
    }

    public function inquire(Request $request)
    {
        $rules = [
            'g-recaptcha-response' => 'required|recaptcha'
        ];
        $messages = [
            'g-recaptcha-response.required' => 'Please verify reCAPTCHA below.',
            'g-recaptcha-response.recaptcha' => 'Please verify reCAPTCHA below.',
        ];
        $validator = $this->validate($request, $rules, $messages);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mode' => $request->mode,
            'phone_no' => $request->phone_no
        ];

        // Create an inquiry
        $inquiry = Inquiry::create($data);

        $message = '';
        if ($request->mode == 'inquiry/demo') {
            $message = 'Someone is interested and wants to request a demo, here are details below:';
        } else {
            $message = 'Someone wants to get in touch, here are details below:';
        }
        $data['content'] = $message;
        
        $email_to = env('MAIL_FROM_ADDRESS');
        if (!empty($email_to)) {
            Mail::to($email_to)->bcc(env('MAIL_FROM_ADDRESS_BCC'))->send(new \App\Mail\InquiryEmail($data));
        }

        return redirect()->to(route('affiliate.index'))->with('success', 'Your information has been submitted.');
    }
}
