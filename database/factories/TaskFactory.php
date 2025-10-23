<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id' => App\User::inRandomOrder()->first()->id,
        'column_id' => App\Column::inRandomOrder()->first()->id,
        'text' => $faker->sentence,
        'active' => 1,
        'order' => $faker->numberBetween(0, 100),
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null
    ];
});

$factory->state(Task::class, 'inactive', function (Faker $faker) {
    return [
        'active' => 0,
    ];
});

$factory->state(Task::class, 'deleted', function (Faker $faker) {
    return [
        'deleted_at' => now(),
    ];
});
