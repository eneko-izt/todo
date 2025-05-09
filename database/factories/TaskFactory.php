<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

//TODO order eremua falta da
$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id' => App\User::inRandomOrder()->first()->id,
        'column_id' => App\Column::inRandomOrder()->first()->id,
        'text' => $faker->sentence,
        'active' => $faker->boolean,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
