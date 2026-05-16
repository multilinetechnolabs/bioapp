<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaddleTransaction extends Model
{
    protected $fillable = [

        'user_id',
        'order_id',
        'plan_id',

        'paddle_transaction_id',
        'paddle_customer_id',
        'paddle_subscription_id',
        'paddle_price_id',

        'amount',
        'currency',
        'status',

        'customer_email',

        'payload',

        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];
}