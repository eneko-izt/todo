<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Test;

class TaskCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Task CRUD: Run the test with vendor/bin/phpunit in attached shell
     * 
     * - A user can only delete their own tasks
     *      -> Has to exist in user table
     *      -> Only user who created the task can update it
     * 
     */


    public function test_User_Can_Delete_Their_Own_Task()
    {
        $defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $defaultColumn = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $userOwner->roles()->attach($defaultRole);
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $defaultColumn->id]);

        $this->assertNull($task->deleted_at, 'Task should not be deleted initially');

        $response = $this->actingAs($userOwner)->delete(route('tasks.delete', ['id' => $task->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_User_Cannot_Delete_Others_Task()
    {
        $defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $defaultColumn = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $userOwner->roles()->attach($defaultRole);
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $defaultColumn->id]);

        $userNotOwner = factory(\App\User::class)->create();
        $userNotOwner->roles()->attach($defaultRole);

        $response = $this->actingAs($userNotOwner)->delete(route('tasks.delete', ['id' => $task->id]));
        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'deleted_at' => null]);
    }

    public function test_User_Can_Update_Their_Own_Task()
    {
        $defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $defaultColumn = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $userOwner->roles()->attach($defaultRole);
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $defaultColumn->id]);

        $response = $this->actingAs($userOwner)->patch(route('tasks.update', ['id' => $task->id]), [
            'text' . $task->id => 'Updated Task',
            'order' . $task->id => 1,
            'column_id' . $task->id => $defaultColumn->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $task->refresh();
        $this->assertEquals('Updated Task', $task->text);
    }

    public function test_User_Not_Owner_Cannot_Update_Task()
    {
        $defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $defaultColumn = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $userOwner->roles()->attach($defaultRole);
        $userNotOwner = factory(\App\User::class)->create();
        $userNotOwner->roles()->attach($defaultRole);
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $defaultColumn->id]);

        $response = $this->actingAs($userNotOwner)->patch(route('tasks.update', ['id' => $task->id]), [
            'text' . $task->id => 'Updated Task',
            'order' . $task->id => 1,
            'column_id' . $task->id => $defaultColumn->id
        ]);
        $response->assertStatus(403);
    }
}
