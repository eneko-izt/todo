<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;
 
    /**
     * Run the test with vendor/bin/phpunit in attached shell
     * OR
     * vendor/bin/phpunit --coverage-html tests/coverage for coverage report
     */

    public function test_user_owner_can_delete_unshared_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->actingAs($userOwner);
        $this->assertTrue($userOwner->can('deleteTask', $task));
    }

    public function test_user_not_owner_cannot_delete_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $anotherUser = factory(User::class)->create();
        $this->actingAs($anotherUser);
        $this->assertFalse($anotherUser->can('deleteTask', $task));
    }

    public function test_user_owner_cannot_delete_shared_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);
        $task->sharingUsers()->attach($anotherUser->id);

        $this->actingAs($userOwner);
        $this->assertFalse($userOwner->can('deleteTask', $task));
    }

    public function test_user_owner_can_edit_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->actingAs($userOwner);
        $this->assertTrue($userOwner->can('editTask', $task));
    }

    public function test_user_not_owner_cannot_edit_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $anotherUser = factory(User::class)->create();
        $this->actingAs($anotherUser);
        $this->assertFalse($anotherUser->can('editTask', $task));
    }

    public function test_user_owner_can_share_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->actingAs($userOwner);
        $this->assertTrue($userOwner->can('shareTask', $task));
    }

    public function test_user_not_owner_cannot_share_task()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $anotherUser = factory(User::class)->create();
        $this->actingAs($anotherUser);
        $this->assertFalse($anotherUser->can('shareTask', $task));
    }

    public function test_user_owner_can_upload_file()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->actingAs($userOwner);
        $this->assertTrue($userOwner->can('uploadFile', $task));
    }

    public function test_user_not_owner_cannot_upload_file()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $anotherUser = factory(User::class)->create();
        $this->actingAs($anotherUser);
        $this->assertFalse($anotherUser->can('uploadFile', $task));
    }
}
