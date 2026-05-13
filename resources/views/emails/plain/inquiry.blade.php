@extends('layouts.mailer_plain')

@section('content')
{{ \App\Support\EmailContentFormatter::toText($content) }}

Full Name: {{ $name }}
Email Address: {{ $email }}
Phone Number: {{ $phone_no ?? '(Not indicated)' }}
@endsection
