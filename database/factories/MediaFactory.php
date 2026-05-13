<?php

use Faker\Generator as Faker;

use App\Models\Media;
use App\Models\User;

$factory->define(Media::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'file_name' => str_random(50),
        's3_name' => str_random(50),
        'description'  => str_random(100)
    ];
});
