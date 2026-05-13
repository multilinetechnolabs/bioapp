<?php

use Faker\Generator as Faker;

use App\Models\Payment;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'amount' => mt_rand(100, 500),
        'date_paid' => \Faker\Provider\DateTime::date(),
    ];
});
