<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Extension::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->randomNumber(),
    ];
});
