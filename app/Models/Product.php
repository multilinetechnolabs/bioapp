<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Product extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description', 'category', 'unit_price', 'size', 'weight'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|unique:products,name',
        'description' => 'required|string',
        'category' => 'required|string',
        'unit_price' => 'required|numeric',
        'size' => 'nullable|string',
        'weight' => 'nullable|string',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weightUnit()
    {
        return explode(' ', $this->weight, 2)[1];
    }

    public function weightValue()
    {
        return floatval(explode(' ', $this->weight, 2)[0]);
    }
}
