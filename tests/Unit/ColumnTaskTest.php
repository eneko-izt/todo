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

        $this->checkHasTasks($this->userAdmin, $this->column, $this->tasksUserAdmin);
        $this->checkHasTasks($this->user1, $this->column, $this->tasksUser1);
        // $this->checkHasTasks($this->userNoRoles, $this->column, $this->tasksUserNoRoles);
    }

    private function checkHasTasks($user, $column, $task)
    {
        $this->actingAs($user);
        $tasks = $column->activeTasks()->get();
        $this->assertTrue($tasks->count() == 1, "Column {$column->name} should have one tasks for {$user->name}");
        $this->assertTrue($tasks[0]->id ==  $task->id, "Column {$column->name} should have tasks for {$user->name}");
    }

    private function populateDatabase()
    {
        $this->userAdmin = factory(\App\User::class)->create(['name' => 'userAdmin', 'active' => 1]);
        $this->user1 = factory(\App\User::class)->create(['name' => 'user1', 'active' => 1]);
        $this->userNoRoles = factory(\App\User::class)->create(['name' => 'userNoRoles', 'active' => 1]);

        $roleAdmin = factory(\App\Role::class)->create(['name' => 'admin']);
        $roleUser = factory(\App\Role::class)->create(['name' => 'user']);

        \App\User::where('name', 'userAdmin')->first()->roles()->attach($roleAdmin->id);
        \App\User::where('name', 'user1')->first()->roles()->attach($roleUser->id);

        $this->column = factory(\App\Column::class)->create([
            'active' => true,
            'deleted_at' => null
        ]);

        $this->tasksUserAdmin = $this->createTask($this->userAdmin, $this->column);
        $this->tasksUser1 = $this->createTask($this->user1, $this->column);
        $this->userNoRoles = $this->createTask(($this->userNoRoles), $this->column);

    }

    private function createTask($user, $column)
    {
        $task = factory(\App\Task::class)->create([
            'user_id' => $user->id,
            'column_id' => $column->id,
            'active' => true,
            'deleted_at' => null
        ]);
        return $task;
    }
}
