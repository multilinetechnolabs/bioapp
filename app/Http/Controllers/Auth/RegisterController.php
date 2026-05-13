<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'type'    => 'required|in:student/estudiante,practitioner/practicante,guest/invitado',
            'name'    => 'required|string|max:255|unique:users',
            'username'=> 'required|string|min:8|unique:users',
            'email'   => 'required|string|email|max:255|unique:users,email',
            'password'=> 'required|string|min:6|confirmed',
            'plan_id' => 'nullable|exists:plans,id',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        DB::beginTransaction();

        try {
            $user = $this->create($request->all());

            DB::commit();

            $this->guard()->login($user);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Registration failed.', [
                'email'     => $request->input('email'),
                'username'  => $request->input('username'),
                'exception' => $e,
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('message.fail', 'Registration failed. Please try again. If the problem continues, contact support.');
        }

        if ($request->filled('plan_id')) {
            session(['pending_plan_id' => $request->plan_id]);
        }

        $mailError = $this->sendVerificationEmail($user);

        if ($mailError) {
            return redirect()->route('verification.notice')
                ->with('message.fail', 'Your account was created, but we could not send the verification email right now. Please use resend and try again in a moment.');
        }

        return redirect()->route('verification.notice')
            ->with('message.success', 'Your account was created successfully. Please check your email to verify your account, then complete your plan selection to activate your account.');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        $user->assignRole($data['type']);

        return $user;
    }

    protected function sendVerificationEmail(User $user)
    {
        if (! method_exists($user, 'sendEmailVerificationNotification')) {
            return false;
        }

        try {
            $user->sendEmailVerificationNotification();

            return false;
        } catch (\Throwable $e) {
            Log::error('Unable to send verification email after registration.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e,
            ]);

            return true;
        }
    }
}
