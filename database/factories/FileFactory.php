<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'task_id' => App\Task::inRandomOrder()->first()->id,
        'filename' => $faker->word . '.' . $faker->fileExtension,
        'path' => $faker->filePath,
        'extension' => $faker->fileExtension,
        'size' => $faker->numberBetween(100, 5000),
    ];
});
