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
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->state('deleted')->create(['column_id' => $column->id, 'user_id' => $user->id]);

        $this->assertSoftDeleted($task);
    }

    public function test_it_can_restore_soft_deleted_tasks()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->state('deleted')->create(['column_id' => $column->id, 'user_id' => $user->id]);

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
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $this->assertEquals($task->column_id, $column->id);
        $this->assertEquals($task->column->id, $column->id);
    }

    public function test_user()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $this->assertEquals($task->user_id, $user->id);
        $this->assertEquals($task->user->id, $user->id);
    }

    public function test_it_has_zero_tags()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $this->assertCount(0, $task->tags);
    }

    public function test_it_has_one_tag()
    {
        $column = factory(Column::class)->create();
        $user = factory(User::class)->create();

        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $tag = factory(Tag::class)->create();
        $deletedTag = factory(Tag::class)->state('deleted')->create();

        // Attach task to tag
        $task->tags()->attach($tag->id);
        $task->tags()->attach($deletedTag->id);

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

        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id]);
        $tag1 = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();
        $tag3 = factory(Tag::class)->create();

        // Attach task to tag
        $task->tags()->attach($tag1->id);
        $task->tags()->attach($tag2->id);
        $task->tags()->attach($tag3->id);

        $tags = collect([$tag1, $tag2, $tag3]);
        $arraysAreEqual = empty(array_diff($tags->pluck('id')->toArray(), $task->tags->pluck('id')->toArray()))
            && empty(array_diff($task->tags->pluck('id')->toArray(), $tags->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }

    public function test_it_has_zero_sharing_users()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->assertCount(0, $task->sharingUsers);
    }

    public function test_it_has_one_sharing_users()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $userActive = factory(User::class)->create();
        $userInactive = factory(User::class)->state('inactive')->create();

        $task->sharingUsers()->attach($userActive->id);
        $task->sharingUsers()->attach($userInactive->id);

        $this->assertCount(1, $task->sharingUsers()->active()->get());
        $this->assertTrue($task->sharingUsers()->active()->get()->contains($userActive));
    }

    public function test_it_has_many_sharing_users()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();
        $userInactive = factory(User::class)->state('inactive')->create();

        // Attach task to user
        $task->sharingUsers()->attach($user1->id);
        $task->sharingUsers()->attach($user2->id);
        $task->sharingUsers()->attach($user3->id);
        $task->sharingUsers()->attach($userInactive->id);

        $this->assertCount(3, $task->sharingUsers()->active()->get());
        $this->assertTrue($task->sharingUsers()->active()->get()->contains($user1));
        $this->assertTrue($task->sharingUsers()->active()->get()->contains($user2));
        $this->assertTrue($task->sharingUsers()->active()->get()->contains($user3));
    }

    public function test_shareable_users()
    {
        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $this->actingAs($userOwner);
        $this->assertCount(0, $task->shareableUsers());

        $userNotOwner1 = factory(User::class)->create();
        $userNotOwner2 = factory(User::class)->create();
        $this->assertCount(2, $task->shareableUsers());
        $this->assertTrue($task->shareableUsers()->pluck('id')->contains($userNotOwner1->id));
        $this->assertTrue($task->shareableUsers()->pluck('id')->contains($userNotOwner2->id));

        $task->sharingUsers()->attach($userNotOwner1->id);
        $this->assertCount(1, $task->shareableUsers());
        $this->assertTrue($task->shareableUsers()->pluck('id')->contains($userNotOwner2->id));

        $task->sharingUsers()->attach($userNotOwner2->id);
        $this->assertCount(0, $task->shareableUsers());
    }   
}
