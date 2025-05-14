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
        // Create a default user easy to remember
        factory(App\User::class)->create([
            'name' => 'izt',
            'email' => 'izt@izt.com',
            'email_verified_at' => now(),
            'password' => bcrypt('izt'),
            'remember_token' => 'izt'
            ]);

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
        });

        $tags = App\Tag::all();

        App\Task::all()->each(function ($task) use ($tags) {
            // Attach random tags to the task
            $task->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });

        // create additional column with no tasks attached
        factory(App\Column::class)->create([
            'name' => 'No tasks'
        ]);

        factory(App\Column::class, 20)->create()->each(function ($column) {
            // Attach a random user to the column
            $column->delete();
        });
    }
}
