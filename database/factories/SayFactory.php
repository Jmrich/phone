<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Say::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'verb' => 'say',
        'noun' => $faker->paragraph(),
    ];
});
