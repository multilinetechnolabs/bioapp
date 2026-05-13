<?php

use Faker\Generator as Faker;

use App\Models\ModelLabel;
use App\Models\Pair;

$factory->define(ModelLabel::class, function (Faker $faker) {
    return [
        'pair_id' => factory(Pair::class)->create()->id,
        'target' => str_random(10),
        'scan_type' => str_random(10),
        'label' => str_random(20),
        'point_x' => mt_rand(1, 100),
        'point_y' => mt_rand(1, 100),
        'point_z' => mt_rand(20, 100)
    ];
});
