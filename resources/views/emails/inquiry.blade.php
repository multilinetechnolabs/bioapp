@extends('layouts.mailer')
@section('styles')
    @parent
@stop
@section('content')
    {!! \App\Support\EmailContentFormatter::toHtml($content) !!}
    <table role="presentation" class="details-table">
        <tr>
            <td class="details-label">Full Name:</td>
            <td>
                {{ $name }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Email Address:</td>
            <td>
                {{ $email }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Phone Number:</td>
            <td>
                {{ $phone_no ?? '(Not indicated)' }}
            </td>
        </tr>
    </table>
@endsection
