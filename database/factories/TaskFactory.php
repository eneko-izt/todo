<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence,
        'active' => $faker->boolean,
        // TODO: foreing keyak falta dira
        'created_at' => now(),
        'updated_at' => now()
    ];
});
