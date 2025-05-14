<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Column;
use Faker\Generator as Faker;

$factory->define(Column::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'colour' => $faker->hexColor,
        'active' => $faker->boolean,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => rand(0, 3) == 0 ? now() : null // Randomly set deleted_at to null or now()
    ];
    // TODO: deleted_at osatzea falta da, aleatorioki null edo datatime bat, horrela Seederrean aparte gehitutakoak ez dira behar 
    // eta logika mantentzeko, ezabatuta dauden zutabeak ezin dute eduki taks-ik.
});
