<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Subscription extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id', 'user_id', 'starts_at', 'ends_at'
    ];

    protected $rules = [
        'plan_id' => 'required|integer|exists:plans,id',
        'user_id' => 'required|integer|exists:users,id',
        'starts_at' => 'nullable|date|before_or_equal:ends_at',
        'ends_at' => 'nullable|date|after_or_equal:starts_at'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'resource');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $appends = ['plan', 'user'];

    public function getPlanAttribute()
    {
        return $this->plan()->first();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
