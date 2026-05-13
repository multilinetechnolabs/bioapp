<?php

use Faker\Generator as Faker;

use Carbon\Carbon;

use App\Models\Activity\Category as ActivityCategory;
use App\Models\User;

$factory->define(ActivityCategory::class, function (Faker $faker) {
    return [
        'name' => str_random(20)
    ];
});
