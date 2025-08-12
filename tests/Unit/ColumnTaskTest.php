<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColumnTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test each user can see their own tasks in a column
     * 
     * - An admin user can see their own tasks
     * - A user can see their own tasks
     * - An admin cannot see another user's tasks
     * - A user cannot see another user's tasks
     */
    public function test_user_can_only_see_own_tasks()
    {
        $roleAdmin = factory(\App\Role::class)->create(['name' => 'admin']);
        $roleUser = factory(\App\Role::class)->create(['name' => 'user']);

        $column = factory(\App\Column::class)->create([
            'active' => true,
            'deleted_at' => null
        ]);

        // Check an admin user can see their own tasks
        $admin = $this->createUser([$roleAdmin->id]);
        $taskAdmin = $this->createTask($admin, $column);
        $this->checkHasTasks($admin, $column, $taskAdmin);

        // Check a user can see their own tasks
        $user = $this->createUser([$roleUser->id]);
        $taskUser = $this->createTask($user, $column);
        $this->checkHasTasks($user, $column, $taskUser);

        // Check a user can see their own tasks
        $anotherUser = $this->createUser([$roleUser->id]);
        $taskAnotherUser = $this->createTask($anotherUser, $column);

        // Check an admin user cannot see another user's tasks
        $this->checkNoOthersTasks($admin, $column, $taskUser);

        // Check a user cannot see another user's tasks
        $this->checkNoOthersTasks($user, $column, $taskAnotherUser);
    }

    private function checkHasTasks($user, $column, $task)
    {
        $this->actingAs($user);
        $tasks = $column->activeTasks()->get();
        $this->assertTrue($tasks->count() == 1, "Column {$column->name} should have one tasks for {$user->name}");
        $this->assertTrue($tasks->contains($task->id), "Column {$column->name} should have tasks for {$user->name}");
    }

    private function checkNoOthersTasks($user, $column, $task)
    {
        $this->actingAs($user);
        $tasks = $column->activeTasks()->get();
        $this->assertFalse($tasks->contains($task->id), "{$user->name} should not have task {$task->id} in column {$column->name}");
    }

    private function createUser($roleIds = [])
    {
        $user = factory(\App\User::class)->create(['active' => 1]);
        $user->roles()->attach($roleIds);
        return $user;
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
