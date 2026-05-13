<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     * Dynamic: go to the pending plan subscribe page, or pricing if none.
     */
    protected $redirectTo = '/pricing';

    protected function redirectPath()
    {
        $planId = session('pending_plan_id');

        if ($planId && \App\Models\Plan::find($planId)) {
            session()->forget('pending_plan_id');
            return route('app.plans.subscribe', ['id' => $planId]);
        }

        if (auth()->user() && auth()->user()->hasValidSubscription()) {
            return route('app.dashboard');
        }

        return route('app.pricing');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // TODO: This bug still persists on Laravel 5.8, may need to clean install the whole project
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Unable to resend verification email.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'exception' => $e,
            ]);

            return back()->with('message.fail', 'We could not send the verification email right now. Please try again shortly.');
        }

        return back()->with('resent', true);
    }
}
