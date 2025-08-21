<?php

use App\Tag;
use App\Role;
use App\Task;
use App\User;
use App\Column;
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
        $userAdmin = factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@izt.eus',
            'email_verified_at' => now(),
            'password' => bcrypt('pasahitza'),
            'remember_token' => ''
        ]);

        factory(User::class, 10)->create();

        factory(Column::class, 5)->create();

        factory(Tag::class, 10)->create();

        factory(Task::class, 20)->create()->each(function ($task) {
            // Attach a random user to the task
            $task->user_id = User::inRandomOrder()->first()->id;
            $task->save();

            // Attach a random column to the task
            $task->column_id = Column::inRandomOrder()->first()->id;
            $task->save();
        });

        $tags = Tag::all();

        Task::all()->each(function ($task) use ($tags) {
            // Attach random tags to the task
            $task->tags()->attach($tags->random(rand(0, 2))->pluck('id')->toArray());
        });

        $roleAdmin = factory(Role::class)->create([
            'name' => 'admin'
        ]);

        $roleUser = factory(Role::class)->create([
            'name' => 'user'
        ]);

        User::where('name', 'admin')->first()->roles()->attach($roleAdmin->id);

        User::where('name', '<>', 'admin')->get()->random(6)->each(function ($user) use ($roleUser) {
            $user->roles()->attach($roleUser->id);
        });

        User::where('name', '<>', 'admin')->get()->random(1)->each(function ($user) use ($roleAdmin) {
            $user->roles()->attach($roleAdmin->id);
        });

        $users = User::where('name', '<>', 'admin')->get()->random(5);
        $users->push($userAdmin);

        Task::where('active', 1)
            ->where('deleted_at', null)
            ->inRandomOrder()->limit(20)
            ->get()
            ->each(function ($task) use ($users) {
                // Attach random users to the task
                $task->sharingUsers()->attach($users->random(rand(1, 3))->pluck('id')->toArray());
        });
    }
}
