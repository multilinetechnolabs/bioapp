<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'is_read', 'email_sent'];

    protected $casts = [
        'is_read'    => 'boolean',
        'email_sent' => 'boolean',
    ];
}
