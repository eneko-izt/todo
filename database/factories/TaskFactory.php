<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id' => App\User::inRandomOrder()->first()->id,
        'column_id' => App\Column::inRandomOrder()->first()->id,
        'text' => $faker->sentence,
        'active' => $faker->boolean,
        'order' => $faker->randomNumber(),
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => rand(0, 3) == 0 ? now() : null // Randomly set deleted_at to null or now()
    ];
});
