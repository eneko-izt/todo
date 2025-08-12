<?php

namespace Tests\Unit;

use App\Column;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColumnModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Column model: Run the test with vendor/bin/phpunit in attached shell
     * 
     */

    public function test_it_has_fillable_attributes()
    {
        $column = new Column([
            'name' => 'Test Column',
            'colour' => '#FFFFFF',
            'active' => true
        ]);

        $this->assertEquals('Test Column', $column->name);
        $this->assertEquals('#FFFFFF', $column->colour);
        $this->assertTrue($column->active);
    }

    public function it_uses_basic_trait()
    {
        $this->assertContains(\App\Traits\BasicTrait::class, class_uses(Task::class));
    }

    public function test_it_has_many_tasks()
    {
        $column = factory(Column::class)->create(['deleted_at' => null]);
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);

        $this->assertTrue($column->tasks->contains($task));
        $this->assertInstanceOf(Task::class, $column->tasks->first());
    }

    public function test_it_returns_only_active_tasks_for_the_authenticated_user()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Task for authenticated user and active
        $taskForUser = factory(Task::class)->create([
            'column_id' => $column->id,
            'user_id' => $user->id,
            'active' => true,
            'order' => 1,
            'deleted_at' => null
        ]);

        // Task for different user
        $taskOtherUser = factory(Task::class)->create([
            'column_id' => $column->id,
            'user_id' => factory(User::class)->create()->id,
            'active' => true,
            'order' => 1,
            'deleted_at' => null
        ]);

        // Inactive task for authenticated user
        $inactiveTask = factory(Task::class)->create([
            'column_id' => $column->id,
            'user_id' => $user->id,
            'active' => false,
            'order' => 2,
            'deleted_at' => null
        ]);

        $activeTasks = $column->activeTasks()->get();

        $this->assertTrue($activeTasks->contains($taskForUser));
        $this->assertFalse($activeTasks->contains($taskOtherUser));
        $this->assertFalse($activeTasks->contains($inactiveTask));
    }

    public function test_it_supports_soft_deletes()
    {
        $column = factory(Column::class)->create();
        $column->delete();

        $this->assertSoftDeleted($column);
    }
}
