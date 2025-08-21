<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'colour' => $faker->hexColor,
        'active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null
    ];
});

$factory->state(Tag::class, 'inactive', function (Faker $faker) {
    return [
        'active' => 0,
    ];
});

$factory->state(Tag::class, 'deleted', function (Faker $faker) {
    return [
        'deleted_at' => now(),
    ];
});
