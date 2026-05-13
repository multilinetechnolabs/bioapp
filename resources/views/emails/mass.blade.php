@extends('layouts.mailer')
@section('content')
    {!! \App\Support\EmailContentFormatter::toHtml($content) !!}
@endsection
