<?php

use Faker\Generator as Faker;

use App\Models\Logo;
use App\Models\User;

$factory->define(Logo::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'file_name' => str_random(50),
        's3_name' => str_random(50),
    ];
});
