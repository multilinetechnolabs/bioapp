<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Plan extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category', 'price'
    ];

    protected $rules = [
        'name' => 'required|string|unique:plans,name',
        'description' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
