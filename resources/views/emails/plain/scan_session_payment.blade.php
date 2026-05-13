@extends('layouts.mailer_plain')

@section('content')
Greetings. Kindly see your scan session details below.

Client Name: {{ $scanSession->client->name }}
Scan Type: {{ ucwords(str_replace('_', ' ', $scanSession->scan_type)) }}
Date Started: {{ $scanSession->date_started }}
Session Type: {{ ucwords($scanSession->cost_type) }}
Session Cost: ${{ number_format($scanSession->cost, 2) }}
@if (!empty($scanSession->date_ended))
Date Ended: {{ $scanSession->date_ended }}
@endif

If you confirm the details above, you may now pay this scan session here:
{{ route('app.scanSessions.payment', ['id' => $scanSession->id]) }}
@endsection
