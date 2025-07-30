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

    private $defaultRole = null;
    private $defaultColumn = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->createDefaultRoleColumn();
    }

    public function testUserCanDeleteTheirOwnTask()
    {
        $userOwner = $this->createUser();
        $task = $this->createTask($userOwner, $this->defaultColumn);

        $this->assertNull($task->deleted_at, 'Task should not be deleted initially');

        $response = $this->actingAs($userOwner)->delete(route('tasks.delete', ['id' => $task->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function testUserCannotDeleteOthersTask()
    {
        $userOwner = $this->createUser();
        $task = $this->createTask($userOwner, $this->defaultColumn);
        $this->assertNull($task->deleted_at, 'Task should not be deleted initially');

        $userNotOwner = $this->createUser();

        $response = $this->actingAs($userNotOwner)->delete(route('tasks.delete', ['id' => $task->id]));
        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'deleted_at' => null]);
    }

    private function createDefaultRoleColumn()
    {
        $this->defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $this->defaultColumn = factory(\App\Column::class)->create(['active' => true, 'deleted_at' => null]);
    }

    private function createUser()
    {
        $user = factory(\App\User::class)->create(['active' => 1]);
        $user->roles()->attach($this->defaultRole);
        return $user;
    }

    private function createTask($user, $column)
    {
        return factory(\App\Task::class)->create([
            'user_id' => $user->id,
            'column_id' => $column->id,
            'active' => true,
            'deleted_at' => null
        ]);
    }
}
