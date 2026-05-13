@extends('layouts.mailer_plain')

@section('content')
Greetings {{ $user->name ?: 'there' }},

You are receiving this email because we received a password reset request for your account.

Reset your password using the link below:
{{ $resetUrl }}

This password reset link will expire in {{ $expireMinutes }} minutes.

If you did not request a password reset, no further action is required.

Best regards,
Anew Team
@endsection
