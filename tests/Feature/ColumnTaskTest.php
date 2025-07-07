<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColumnTaskTest extends TestCase
{
    use RefreshDatabase;

    private $userAdmin;
    private $user1;
    private $userNoRoles;

    private $column;

    private $tasksUserAdmin;
    private $tasksUser1;
    private $tasksUserNoRoles;

    public function testOnlyOwnTasks()
    {
        $this->populateDatabase();

        $user = $this->actingAs(\App\User::where('name', 'userAdmin')->first());
        $tasks = $this->column->activeTasks()->get();
        $this->assertTrue($tasks->count() > 0, "Column {$this->column->name} should have tasks for {$user->name}");

        $user = $this->actingAs(\App\User::where('name', 'userAdmin')->first());
        $tasks = $this->column->activeTasks()->get();
        $this->assertTrue($tasks->count() > 0, "Column {$this->column->name} should have tasks for {$user->name}");

        $user = $this->actingAs(\App\User::where('name', 'user2')->first());
        $tasks = $this->column->activeTasks()->get();
        $this->assertTrue($tasks->count() > 0, "Column {$this->column->name} should have tasks for {$user->name}");

    }

    function populateDatabase()
    {
        $this->userAdmin = factory(\App\User::class)->create(['name' => 'userAdmin']);
        $this->user1 = factory(\App\User::class)->create(['name' => 'user1']);
        $this->userNoRoles = factory(\App\User::class)->create(['name' => 'userNoRoles']);

        $roleAdmin = factory(\App\Role::class)->create([
            'name' => 'admin'
        ]);

        $roleUser = factory(\App\Role::class)->create([
            'name' => 'user'
        ]);

        \App\User::where('name', 'userAdmin')->first()->roles()->attach($roleAdmin->id);
        \App\User::where('name', 'user1')->first()->roles()->attach($roleUser->id);

        $this->column = factory(\App\Column::class)->create();

        $this->tasksUserAdmin = factory(\App\Task::class)->create();
        $this->tasksUserAdmin->user()->attach($this->userAdmin->id);
        $this->tasksUserAdmin->column()->attach($this->column->id);
        $this->tasksUserAdmin->save();

        $this->tasksUser1 = factory(\App\Task::class)->create();
        $this->tasksUser1->user()->attach($this->user1->id);
        $this->tasksUser1->column()->attach($this->column->id);
        $this->tasksUser1->save();

        $this->tasksUserNoRoles = factory(\App\Task::class)->create();
        $this->tasksUserNoRoles->user()->attach($this->userNoRoles->id);
        $this->tasksUserNoRoles->column()->attach($this->column->id);
        $this->tasksUserNoRoles->save();
    }
}
