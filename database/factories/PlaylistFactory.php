<?php

use Faker\Generator as Faker;

use App\Models\Playlist;
use App\Models\User;

$factory->define(Playlist::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'name' => str_random(50),
        'description'  => str_random(100)
    ];
});
