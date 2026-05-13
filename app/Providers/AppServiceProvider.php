<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $forceHttps = filter_var(
            env('APP_HTTPS', $this->app->environment('production')),
            FILTER_VALIDATE_BOOLEAN
        );

        if ($forceHttps) {
            URL::forceScheme('https');
        }

        \Validator::extend('recaptcha', 'App\Validators\ReCaptcha@validate');

        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->view([
                    'html' => 'emails.auth.verify',
                    'text' => 'emails.plain.auth.verify',
                ], [
                    'user' => $notifiable,
                    'verificationUrl' => $verificationUrl,
                ]);
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $passwordBroker = config('auth.defaults.passwords');
            $resetUrl = url(config('app.url').route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password Notification')
                ->view([
                    'html' => 'emails.auth.reset_password',
                    'text' => 'emails.plain.auth.reset_password',
                ], [
                    'user' => $notifiable,
                    'resetUrl' => $resetUrl,
                    'expireMinutes' => config('auth.passwords.'.$passwordBroker.'.expire', 60),
                ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
