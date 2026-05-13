<?php

use Faker\Generator as Faker;

use Carbon\Carbon;

use App\Models\Activity;
use App\Models\Activity\Category as ActivityCategory;
use App\Models\User;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'user_id' =>  factory(User::class)->create()->id,
        'category' => str_random(20),
        'title' => str_random(20),
        'content' => str_random(20),
        'date_published' => Carbon::now()->format('Y-m-d')
    ];
});
