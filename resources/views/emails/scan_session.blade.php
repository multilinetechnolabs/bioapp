@extends('layouts.mailer')
@section('styles')
    @parent
@stop
@section('content')
    @if (!empty($content))
        {!! \App\Support\EmailContentFormatter::toHtml($content) !!}
    @endif
    <p>The completed scan session summary is below.</p>
    <table role="presentation" class="details-table">
        <tr>
            <td class="details-label">Client Name:</td>
            <td>
                {{ $scan_session->client->name }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Scan Type:</td>
            <td>
                {{ ucwords(str_replace('_', ' ', $scan_session->scan_type)) }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Date Started:</td>
            <td>
                {{ $scan_session->date_started }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Date Ended:</td>
            <td>
                {{ $scan_session->date_ended ?? '-' }}
            </td>
        </tr>
    </table>
    <p class="email-note">The complete scan worksheet is attached for your records.</p>
@endsection
