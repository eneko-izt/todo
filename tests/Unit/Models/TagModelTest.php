<?php

namespace Tests\Unit;

use App\Tag;
use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TagModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fillable_attributes()
    {
        $tag = new Tag();

        $columnNames = ['name', 'active', 'colour'];
        
        $arraysAreEqual = empty(array_diff($columnNames, $tag->getFillable()))
            && empty(array_diff($tag->getFillable(), $columnNames));
        $this->assertTrue($arraysAreEqual);
    }

    public function it_uses_basic_trait()
    {
        $this->assertContains(\App\Traits\BasicTrait::class, class_uses(Tag::class));
    }

    public function test_it_supports_soft_deletes()
    {
        $tag = factory(Tag::class)->create();
        $tag->delete();

        $this->assertSoftDeleted($tag);
    }

    public function test_it_can_restore_soft_deleted_tags()
    {
        $tag = factory(Tag::class)->create();
        $tag->delete();

        $this->assertSoftDeleted($tag);
        $this->assertNotNull($tag->deleted_at);

        $tag->restore();

        $this->assertNull($tag->deleted_at);
    }

    public function test_tasks_relationship_is_belongs_to_many()
    {
        $tag = new Tag();
        $relation = $tag->tasks();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('tag_task', $relation->getTable());
    }

    public function test_it_has_zero_tasks()
    {
        $tag = factory(Tag::class)->create();
        $this->assertCount(0, $tag->tasks);
    }

    public function test_it_has_one_task()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $tag = factory(Tag::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id]);
        $deletedTask = factory(Task::class)->state('deleted')->create(['column_id' => $column->id, 'user_id' => $user->id]);

        // Attach task to tag
        $tag->tasks()->attach($task->id);
        $tag->tasks()->attach($deletedTask->id);

        $notUsedTag = factory(Tag::class)->create();
        $notUsedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);

        // Attach unused task to unused tag
        $notUsedTag->tasks()->attach($notUsedTask->id);

        $this->assertTrue($tag->tasks->contains($task));
        $this->assertInstanceOf(Task::class, $tag->tasks->first());
    }

    public function test_it_has_many_tasks()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $tag = factory(Tag::class)->create();
        $task1 = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id]);
        $task2 = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id]);
        $task3 = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id]);

        // Attach task to tag
        $tag->tasks()->attach($task1->id);
        $tag->tasks()->attach($task2->id);
        $tag->tasks()->attach($task3->id);

        $tasks = collect([$task1, $task2, $task3]);
        $arraysAreEqual = empty(array_diff($tasks->pluck('id')->toArray(), $tag->tasks->pluck('id')->toArray()))
            && empty(array_diff($tag->tasks->pluck('id')->toArray(), $tasks->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }
}
