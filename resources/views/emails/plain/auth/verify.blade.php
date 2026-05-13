@extends('layouts.mailer_plain')

@section('content')
Greetings {{ $user->name ?: 'there' }},

Please click the link below to verify your email address:
{{ $verificationUrl }}

If you did not create an account, no further action is required.

Best regards,
Anew Team
@endsection
