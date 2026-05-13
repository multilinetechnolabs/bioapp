@extends('layouts.mailer_plain')

@section('content')
Greetings {{ $order->user->name }},

Here are the details for your order. You may pay for your order here:
{{ env('APP_URL').'/orders/'.$order->id.'/payment' }}

Product Name: {{ $order->description }}
Price per unit: {{ '$'.number_format($order->product->unit_price, 2) }}
Quantity: {{ $order->quantity }}
@if (!empty($order->shipping_service))
Shipping Service: {{ $order->shipping_service }}
@endif
@if (!empty($order->shipping_day_set))
Shipping Duration: {{ $order->shipping_day_set }}
@endif
@if (!empty($order->shipping_rate))
Shipping Fee: {{ '$'.number_format($order->shipping_rate, 2) }}
@endif
Total Cost: {{ '$'.number_format($order->cost(), 2) }}

Best regards,
Anew Team
@endsection
