<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Product;

use Watson\Validating\ValidatingTrait;

class Order extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'description', 'shipping_address', 'shipping_zip',
        'shipping_service', 'will_shipping', 'shipping_day_set', 'shipping_rate'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'product_id' => 'required|integer|exists:products,id',
        'quantity' => 'required|numeric|min:1',
        'description' => 'required|string',
        'shipping_address' => 'required|string',
        'shipping_service' => 'required|string',
        'shipping_zip' => 'required|integer',
        'will_shipping' => 'required|string',
        'shipping_day_set' => 'required|string',
        'shipping_rate' => 'required|numeric',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'resource');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cost()
    {
        return ($this->product->unit_price * $this->quantity) + $this->shipping_rate;
    }

    public function totalWeight()
    {
        $weight = floatval(explode(' ', $this->product->weight, 2)[0]);
        return $weight * $this->quantity;
    }

    public function paid()
    {
        return !empty($this->payment);
    }

    public function datePaid()
    {
        if (!empty($this->payment)) {
            return $this->payment->created_at;
        }
    }

    protected $appends = ['cost', 'datePaid', 'paid', 'payment', 'product', 'user'];

    public function getCostAttribute()
    {
        return number_format($this->cost(), 2);
    }

    public function getDatePaidAttribute()
    {
        return $this->datePaid();
    }

    public function getPaidAttribute()
    {
        return $this->paid();
    }

    public function getPaymentAttribute()
    {
        return $this->payment()->first();
    }

    public function getProductAttribute()
    {
        return $this->product()->first();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
