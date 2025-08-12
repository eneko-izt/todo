<?php

namespace Tests\Unit;

use App\Tag;
use App\Task;
use App\Column;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fillable_attributes()
    {
        $tag = new Tag([
            'name' => 'Urgent',
            'colour' => '#FF0000',
            'active' => true
        ]);

        $this->assertEquals('Urgent', $tag->name);
        $this->assertEquals('#FF0000', $tag->colour);
        $this->assertTrue($tag->active);
    }

    public function test_it_has_a_many_to_many_relationship_with_tasks()
    {
        $column = factory(Column::class)->create(['active' => true, 'deleted_at' => null]);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $tag = factory(Tag::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'active' => true, 'user_id' => $user->id, 'deleted_at' => null]);

        // Attach task to tag
        $tag->tasks()->attach($task->id);

        $this->assertTrue($tag->tasks->contains($task));
        $this->assertInstanceOf(Task::class, $tag->tasks->first());
    }

    public function test_it_supports_soft_deletes()
    {
        $tag = factory(Tag::class)->create();
        $tag->delete();

        $this->assertSoftDeleted($tag);
    }}
