<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => '+15005550006',
        'auth_id' => env('TWILIO_TEST_AUTH_ID'),
        'auth_token' => env('TWILIO_TEST_AUTH_TOKEN')
    ];
});
