@extends('layouts.mailer')

@section('content')
    <p>Greetings {{ $user->name ?: 'there' }},</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>
        <a href="{{ $resetUrl }}" class="email-button">Reset Password</a>
    </p>
    <p class="email-note">This password reset link will expire in {{ $expireMinutes }} minutes.</p>
    <p class="email-note">If the button does not work, copy and paste this link into your browser:</p>
    <p>
        <a href="{{ $resetUrl }}" class="email-link">{{ $resetUrl }}</a>
    </p>
    <p class="email-footer">
        If you did not request a password reset, no further action is required.<br>
        Best regards,<br>
        Anew Team
    </p>
@endsection
