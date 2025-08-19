<?php

namespace Tests\Unit;

use App\Tag;
use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_it_has_correct_fillable_attributes()
    {
        $task = new Task();

        $columnNames = ['text', 'order', 'user_id', 'column_id', 'active'];

        $arraysAreEqual = empty(array_diff($columnNames, $task->getFillable()))
            && empty(array_diff($task->getFillable(), $columnNames));
        $this->assertTrue($arraysAreEqual);
    }

    public function test_it_uses_basic_trait()
    {
        $this->assertContains(\App\Traits\BasicTrait::class, class_uses(Task::class));
    }

    public function test_it_supports_soft_deletes()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $task->delete();

        $this->assertSoftDeleted($task);
    }

    public function test_it_can_restore_soft_deleted_tasks()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $task->delete();

        $this->assertSoftDeleted($task);
        $this->assertNotNull($task->deleted_at);

        $task->restore();

        $this->assertNull($task->deleted_at);
    }

    public function test_user_relationship_is_belongs_to()
    {
        $task = new Task();
        $relation = $task->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
    }

    public function test_column_relationship_is_belongs_to()
    {
        $task = new Task();
        $relation = $task->column();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('column_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
    }

    public function test_tags_relationship_is_belongs_to_many()
    {
        $task = new Task();
        $relation = $task->tags();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('tag_task', $relation->getTable());
    }

    public function test_column()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $this->assertEquals($task->column_id, $column->id);
        $this->assertEquals($task->column->id, $column->id);
    }

    public function test_user()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $this->assertEquals($task->user_id, $user->id);
        $this->assertEquals($task->user->id, $user->id);
    }

    public function test_it_has_zero_tags()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $this->assertCount(0, $task->tags);
    }

    public function test_it_has_one_tag()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $tag = factory(Tag::class)->create(['deleted_at' => null]);
        $deletedTag = factory(Tag::class)->create(['deleted_at' => now()]);

        // Attach task to tag
        $task->tags()->attach($tag->id);
        $task->tags()->attach($deletedTag->id);

        $notUsedTag = factory(Tag::class)->create(['deleted_at' => null]);
        $notUsedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);

        // Attach unused task to unused tag
        $notUsedTag->tasks()->attach($notUsedTask->id);

        $this->assertTrue($tag->tasks->contains($task));
        $this->assertInstanceOf(Task::class, $tag->tasks->first());
    }

    public function test_it_has_many_tasks()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);
        $tag1 = factory(Tag::class)->create(['deleted_at' => null]);
        $tag2 = factory(Tag::class)->create(['deleted_at' => null]);
        $tag3 = factory(Tag::class)->create(['deleted_at' => null]);

        // Attach task to tag
        $task->tags()->attach($tag1->id);
        $task->tags()->attach($tag2->id);
        $task->tags()->attach($tag3->id);

        $tags = collect([$tag1, $tag2, $tag3]);
        $arraysAreEqual = empty(array_diff($tags->pluck('id')->toArray(), $task->tags->pluck('id')->toArray()))
            && empty(array_diff($task->tags->pluck('id')->toArray(), $tags->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }
}
