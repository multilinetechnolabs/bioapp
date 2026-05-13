@extends('layouts.mailer')
@section('styles')
    @parent
@stop
@section('content')
    <p>Greetings {{ $order->user->name }},</p>
    <p>
        Here are the details for your order. You may pay for your order by clicking
        <a href="{{ env('APP_URL').'/orders/'.$order->id.'/payment' }}">here</a>.
        Thank you for choosing our products.
    </p>
    <table role="presentation" class="details-table">
        <tr>
            <td class="details-label">Product Name:</td>
            <td>
                {{ $order->description }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Price per unit:</td>
            <td>
                {{ '$'.number_format($order->product->unit_price, 2) }}
            </td>
        </tr>
        <tr>
            <td class="details-label">Quantity:</td>
            <td>
                {{ $order->quantity }}
            </td>
        </tr>
        @if (!empty($order->shipping_service))
        <tr>
            <td class="details-label">Shipping Service:</td>
            <td>
            {{ $order->shipping_service }}
            </td>
        </tr>
        @endif
        @if (!empty($order->shipping_day_set))
        <tr>
            <td class="details-label">Shipping Duration:</td>
            <td>
            {{ $order->shipping_day_set }}
            </td>
        </tr>
        @endif
        @if (!empty($order->shipping_rate))
        <tr>
            <td class="details-label">Shipping Fee:</td>
            <td>
            {{ '$'.number_format($order->shipping_rate, 2) }}
            </td>
        </tr>
        @endif
        <tr>
            <td class="details-label">Total Cost:</td>
            <td>
                {{ '$'.number_format($order->cost(), 2) }}
            </td>
        </tr>
    </table>
    <p class="email-footer">
        Best regards,<br>
        Anew Team
    </p>
@endsection
