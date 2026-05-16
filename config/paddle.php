<?php

return [

    'api_key' => env('PADDLE_API_KEY'),

    'client_token' => env('PADDLE_CLIENT_TOKEN'),

    'monthly_price_id' => env('PADDLE_MONTHLY_PRICE_ID'),

    'yearly_price_id' => env('PADDLE_YEARLY_PRICE_ID'),

    'webhook_secret' => env('PADDLE_WEBHOOK_SECRET'),

    'environment' => env('PADDLE_ENV', 'sandbox'),

];