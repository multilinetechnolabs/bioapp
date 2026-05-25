<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreemiusTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'freemius_transaction_id',
        'freemius_subscription_id',
        'freemius_license_key',
        'amount',
        'currency',
        'status',
        'customer_email',
        'payload',
        'paid_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
    ];
}
