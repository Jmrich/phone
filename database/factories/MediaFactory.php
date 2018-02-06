<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Media::class, function (Faker\Generator $faker) {
    return [
        'filename' => str_random(32),
        'extension' => $faker->fileExtension
    ];
});
