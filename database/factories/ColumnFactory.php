<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Column;
use Faker\Generator as Faker;

$factory->define(Column::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'colour' => $faker->hexColor,
        'active' => $faker->boolean,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => rand(0, 3) == 0 ? now() : null // Randomly set deleted_at to null or now()
    ];
});
