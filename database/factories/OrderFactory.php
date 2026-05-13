<?php

use Faker\Generator as Faker;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'product_id' => factory(Product::class)->create()->id,
        'description' => str_random(20),
        'quantity' => mt_rand(1, 10),
        'shipping_service' => str_random(20),
        'will_shipping' => str_random(20),
        'shipping_address' => str_random(20),
        'shipping_day_set' => str_random(20),
        'shipping_zip' => \Faker\Provider\Base::randomNumber(6),
        'shipping_rate' => \Faker\Provider\Base::randomFloat(2, 10, 50),
    ];
});
