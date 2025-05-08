<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence,
        'active' => $faker->boolean,
        'column_id' => App\Column::inRandomOrder()->first()->id,
        'user_id' => App\User::inRandomOrder()->first()->id,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
