<?php

use Faker\Generator as Faker;

use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;

use Carbon\Carbon;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'plan_id' => factory(Plan::class)->create()->id,
        'starts_at' => Carbon::now()->format('Y-m-d'),
        'ends_at' => Carbon::now()->addDays(mt_rand(30, 365))->format('Y-m-d')
    ];
});
