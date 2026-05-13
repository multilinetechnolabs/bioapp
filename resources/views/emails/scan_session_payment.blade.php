@extends('layouts.mailer')
@section('styles')
    @parent
@stop
@section('content')
    <p>Greetings. Kindly see your scan session details below.</p>
    <table role="presentation" class="details-table">
        <tr>
            <td class="details-label">Client Name:</td>
            <td>
                {{ $scanSession->client->name }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Scan Type:</td>
            <td>
                {{ ucwords(str_replace('_', ' ', $scanSession->scan_type)) }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Date Started:</td>
            <td>
                {{ $scanSession->date_started }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Session Type:</td>
            <td>
                {{ ucwords($scanSession->cost_type)}}
            </td>
        </tr>
        <tr>
            <td class="details-label">Session Cost:</td>
            <td>
                ${{ number_format($scanSession->cost, 2) }}
            </td>
        </tr>
        @if (!empty($scanSession->date_ended))
        <tr>
            <td class="details-label">Date Ended:</td>
            <td>
                {{ $scanSession->date_ended }}
            </td>
        </tr>
        @endif
    </table>
    <p>
        If you confirm the details above, you may now pay this scan session by clicking this
        <a href="{{ route('app.scanSessions.payment', ['id' => $scanSession->id]) }}">link</a>.
    </p>
@endsection
