<?php

use Faker\Generator as Faker;

use App\Models\Pair;

$factory->define(Pair::class, function (Faker $faker) {
    return [
        'scan_type' => str_random(10),
        'name' => $faker->name,
        'ref_no' => str_random(20),
        'radical' => str_random(20),
        'origins' => str_random(20),
        'symptoms' => str_random(20),
        'paths' => str_random(20),
        'alternative_routes' => str_random(20),
        'guided_ref_no' => str_random(20)
    ];
});
