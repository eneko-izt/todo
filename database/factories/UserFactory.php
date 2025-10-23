<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Classes\Languages;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'active' => 1, // Default to active
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'language' => array_rand(Languages::getAll()),
    ];
});

$factory->state(User::class, 'inactive', function (Faker $faker) {
    return [
        'active' => 0,
    ];
});
