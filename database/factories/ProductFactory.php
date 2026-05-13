<?php

use Faker\Generator as Faker;

use App\Models\Product;
use App\Models\User;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'name' => $faker->name,
        'description' => str_random(30),
        'category' => str_random(20),
        'unit_price' => mt_rand(20, 100),
        'size' => str_random(10)
    ];
});
