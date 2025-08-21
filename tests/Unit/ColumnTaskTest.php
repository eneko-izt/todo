<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColumnTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_own_tasks()
    {
        $user = factory(\App\User::class)->create();
        $column = factory(\App\Column::class)->create();
        $task = factory(\App\Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id]);

        $this->actingAs($user);
        $tasks = $column->activeTasks()->get();
        $this->assertTrue($tasks->count() == 1);
        $this->assertTrue($tasks->contains($task->id));
    }

    public function test_cannot_see_other_users_tasks()
    {
        $user = factory(\App\User::class)->create();
        $column = factory(\App\Column::class)->create();
        $task = factory(\App\Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id]);

        $userNotOwner = factory(\App\User::class)->create();
        $this->actingAs($userNotOwner);

        $tasks = $column->activeTasks()->get();
        $this->assertTrue($tasks->count() == 0);
        $this->assertFalse($tasks->contains($task->id));
    }

}
