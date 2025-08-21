<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class TaskCRUDTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Test Task CRUD: Run the test with vendor/bin/phpunit in attached shell
     */

    private $column;
    private $userOwner;
    private $userNotOwner;
    private $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->fillDatabase();
    }

    public function test_User_Can_Delete_Their_Own_Task()
    {
        $this->assertNull($this->task->deleted_at, 'Task should not be deleted initially');

        $response = $this->actingAs($this->userOwner)->delete(route('tasks.delete', ['id' => $this->task->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertSoftDeleted('tasks', ['id' => $this->task->id]);
    }

    public function test_User_Cannot_Delete_Others_Task()
    {
        $response = $this->actingAs($this->userNotOwner)->delete(route('tasks.delete', ['id' => $this->task->id]));
        $response->assertStatus(403);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'deleted_at' => null]);
    }

    public function test_User_Can_Update_Their_Own_Task()
    {
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.update', ['id' => $this->task->id]), [
            'text' . $this->task->id => 'Updated Task',
            'order' . $this->task->id => 1,
            'column_id' . $this->task->id => $this->column->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->task->refresh();
        $this->assertEquals('Updated Task', $this->task->text);
    }

    public function test_User_Not_Owner_Cannot_Update_Task()
    {
        $response = $this->actingAs($this->userNotOwner)->patch(route('tasks.update', ['id' => $this->task->id]), [
            'text' . $this->task->id => 'Updated Task',
            'order' . $this->task->id => 1,
            'column_id' . $this->task->id => $this->column->id
        ]);
        $response->assertStatus(403);
    }

    public function test_User_Can_Share_Unshare_Their_Own_Task()
    {
        // Sharing a task
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);

        // Sharing the task again
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        // Assert the user is attached only once
        $this->assertEquals(1, DB::table('task_user')
                ->where('task_id', $this->task->id)
                ->where('user_id', $this->userNotOwner->id)
                ->count());

        // Unsharing the task
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.unshare', ['id' => $this->task->id, 'userId' => $this->userNotOwner->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        // Assert the user is unshared with a softdelete
        $this->assertDatabaseMissing('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);

        // Sharing the task again
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        // Assert the user is attached only once
        $this->assertEquals(1, DB::table('task_user')
                ->where('task_id', $this->task->id)
                ->where('user_id', $this->userNotOwner->id)
                ->count());
    }

    public function test_User_Not_Owner_Cannot_Share_Task()
    {
        $response = $this->actingAs($this->userNotOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);
    }

    public function test_User_Not_Owner_Cannot_Unshare_Task()
    {
        // Sharing a task
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);

        $response = $this->actingAs($this->userNotOwner)->patch(route('tasks.unshare', ['id' => $this->task->id, 'userId' => $this->userNotOwner->id]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);
    }

    public function test_User_Can_View_Their_Own_Task()
    {
        $this->actingAs($this->userOwner);
        $this->assertTrue($this->column->viewableTasks()->contains($this->task), 'User should be able to view their own task');
    }

    public function test_User_Not_Owner_Can_View_Shared_Task()
    {
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);

        $this->actingAs($this->userNotOwner);
        $this->assertTrue($this->column->viewableTasks()->contains($this->task), 'User should be able to view their own task');
    }

    public function test_User_Not_Owner_Cannot_View_Unshared_Task()
    {
        $response = $this->actingAs($this->userOwner)->patch(route('tasks.share', ['id' => $this->task->id]), [
            'userid' => $this->userNotOwner->id
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_user', ['task_id' => $this->task->id, 'user_id' => $this->userNotOwner->id]);

        $anotherUser = factory(\App\User::class)->create();

        $this->actingAs($anotherUser);
        $this->assertFalse($this->column->viewableTasks()->contains($this->task), 'User should be able to view their own task');
    }

    private function fillDatabase()
    {
        $this->column = factory(\App\Column::class)->create();
        $this->userOwner = factory(\App\User::class)->create();
        $this->userNotOwner = factory(\App\User::class)->create();
        $this->task = factory(\App\Task::class)->create(['user_id' => $this->userOwner->id, 'column_id' => $this->column->id]);
    }
}
