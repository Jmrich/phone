<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Menu::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->streetName
    ];
});
