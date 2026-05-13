@extends('layouts.mailer_plain')

@section('content')
{{ \App\Support\EmailContentFormatter::toText($content) }}
@endsection
