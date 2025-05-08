<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 users
        factory(App\User::class, 10)->create();

        factory(App\Column::class, 3)->create();

        factory(App\Tag::class, 10)->create();


        factory(App\Task::class, 20)->create()->each(function ($task) {
            // Attach a random user to the task
            $task->user_id = App\User::inRandomOrder()->first()->id;
            $task->save();

            // Attach a random column to the task
            $task->column_id = App\Column::inRandomOrder()->first()->id;
            $task->save();

            // Attach random tags to the task
            // TODO: rand???? eta 100 tag baldin badaude? Bakarrik lehengo hirurak erabiliko dira?
            $tags = App\Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $task->tags()->attach($tags);
        });
    }
}
