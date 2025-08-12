<?php

namespace Tests\Unit;

use App\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    public function test_it_has_correct_fillable_attributes()
    {
        $task = new Task();

        $this->assertEquals(['text', 'order', 'user_id', 'column_id', 'active'],
            $task->getFillable()
        );
    }

    public function test_it_uses_soft_deletes_trait()
    {
        $this->assertContains(SoftDeletes::class, class_uses(Task::class));
    }

    public function test_it_uses_basic_trait()
    {
        $this->assertContains(\App\Traits\BasicTrait::class, class_uses(Task::class));
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
}
