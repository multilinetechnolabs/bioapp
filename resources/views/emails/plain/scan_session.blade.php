@extends('layouts.mailer_plain')

@section('content')
@if (!empty($content))
{{ \App\Support\EmailContentFormatter::toText($content) }}

@endif
Scan Session Summary
--------------------

Client Name: {{ $scan_session->client->name }}
Scan Type: {{ ucwords(str_replace('_', ' ', $scan_session->scan_type)) }}
Date Started: {{ $scan_session->date_started }}
Date Ended: {{ $scan_session->date_ended ?? '-' }}

The complete scan worksheet is attached for your records.
@endsection
