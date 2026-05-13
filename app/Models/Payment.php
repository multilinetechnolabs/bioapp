<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Payment extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resource_id', 'resource_type', 'amount', 'date_paid', 'description'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'resource_id' => 'nullable|integer',
        'resource_type' => 'nullable|string',
        'amount' => 'required|numeric',
        'date_paid' => 'required|date',
        'description' => 'nullable|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function resource()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scanSessions()
    {
        return $this->hasMany(ScanSession::class);
    }
}
