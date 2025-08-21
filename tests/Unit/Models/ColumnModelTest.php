<?php

namespace Tests\Unit;

use App\Column;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ColumnModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Column model: Run the test with vendor/bin/phpunit in attached shell
     * 
     */

    public function test_it_has_correct_fillable_attributes()
    {
        $column = new Column();

        $columnNames = ['name', 'colour', 'active'];

        $arraysAreEqual = empty(array_diff($columnNames, $column->getFillable()))
            && empty(array_diff($column->getFillable(), $columnNames));
        $this->assertTrue($arraysAreEqual);
    }

    public function it_uses_basic_trait()
    {
        $this->assertContains(\App\Traits\BasicTrait::class, class_uses(Task::class));
    }

    public function test_it_supports_soft_deletes()
    {
        $column = factory(Column::class)->create();
        $column->delete();

        $this->assertSoftDeleted($column);
    }

    public function test_it_can_restore_soft_deleted_columns()
    {
        $column = factory(Column::class)->create();
        $column->delete();

        $this->assertSoftDeleted($column);
        $this->assertNotNull($column->deleted_at);

        $column->restore();

        $this->assertNull($column->deleted_at);
    }

    public function test_tasks_relationship_is_has_many()
    {
        $column = new Column();
        $relation = $column->tasks();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('column_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getLocalKeyName());
    }

    public function test_it_has_zero_task()
    {
        $column = factory(Column::class)->create();
        $this->assertCount(0, $column->tasks);
    }

    public function test_it_has_one_task()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $deletedTask = factory(Task::class)->state('deleted')->create(['column_id' => $column->id, 'user_id' => $user->id]);

        $notUsedColumn = factory(Column::class)->create();
        $notUsedTask = factory(Task::class)->create(['column_id' => $notUsedColumn->id, 'user_id' => $user->id]);

        $this->assertTrue($column->tasks->contains($task));
        $this->assertCount(1, $column->tasks);
        $this->assertInstanceOf(Task::class, $column->tasks->first());
    }

    public function test_it_has_many_tasks()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();
        $task1 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $task2 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $task3 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $deletedTask = factory(Task::class)->state('deleted')->create(['column_id' => $column->id, 'user_id' => $user->id]);

        $notUsedColumn = factory(Column::class)->create();
        $notUsedTask = factory(Task::class)->create(['column_id' => $notUsedColumn->id, 'user_id' => $user->id]);

        $tasks = collect([$task1, $task2, $task3]);
        $arraysAreEqual = empty(array_diff($tasks->pluck('id')->toArray(), $column->tasks->pluck('id')->toArray()))
            && empty(array_diff($column->tasks->pluck('id')->toArray(), $tasks->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }

    public function test_it_returns_only_active_tasks_for_the_authenticated_user()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Task for authenticated user and active
        $taskForUser = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);

        // Task for different user
        $taskOtherUser = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => factory(User::class)->create()->id]);

        // Inactive task for authenticated user
        $inactiveTask = factory(Task::class)->state('inactive')->create(['column_id' => $column->id, 'user_id' => $user->id]);

        $activeTasks = $column->activeTasks()->get();

        $this->assertTrue($activeTasks->contains($taskForUser));
        $this->assertFalse($activeTasks->contains($taskOtherUser));
        $this->assertFalse($activeTasks->contains($inactiveTask));
    }
}
