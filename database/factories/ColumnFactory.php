<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Column;
use Faker\Generator as Faker;

$factory->define(Column::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'colour' => $faker->hexColor,
        'active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null
    ];
});

$factory->state(Column::class, 'inactive', function (Faker $faker) {
    return [
        'active' => 0,
    ];
});

$factory->state(Column::class, 'deleted', function (Faker $faker) {
    return [
        'deleted_at' => now(),
    ];
});
