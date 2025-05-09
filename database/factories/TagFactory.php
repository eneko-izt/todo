<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'colour' => $faker->hexColor,
        'active' => $faker->boolean,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
