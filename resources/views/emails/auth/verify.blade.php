@extends('layouts.mailer')

@section('content')
    <p>Greetings {{ $user->name ?: 'there' }},</p>
    <p>Please click the button below to verify your email address.</p>
    <p>
        <a href="{{ $verificationUrl }}" class="email-button">Verify Email Address</a>
    </p>
    <p class="email-note">If the button does not work, copy and paste this link into your browser:</p>
    <p>
        <a href="{{ $verificationUrl }}" class="email-link">{{ $verificationUrl }}</a>
    </p>
    <p class="email-footer">
        If you did not create an account, no further action is required.<br>
        Best regards,<br>
        Anew Team
    </p>
@endsection
