<?php

use Faker\Generator as Faker;

use App\Models\Plan;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => str_random(30),
        'category' => str_random(20),
        'price' => mt_rand(20, 100),
    ];
});
