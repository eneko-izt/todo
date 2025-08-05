<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Tag model: Run the test with vendor/bin/phpunit in attached shell
     *
     * Requirements:
     *      -> Tag name must be unique
     *      -> Tags can be assigned to multiple tasks and active trait functionality must work correctly
     *
     */

    private const numberOfActiveTags = 10;
    private const numberOfInactiveTags = 7;

    private $role = null;
    private $user = null;
    private $column = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->role = factory(\App\Role::class)->create(['name' => 'user']);
        $this->user = factory(\App\User::class)->create(['active' => 1]);
        $this->user->roles()->attach($this->role);
        $this->column = factory(\App\Column::class)->create(['active' => true, 'deleted_at' => null]);
    }

    public function testTagNameUnique()
    {
        $tag = factory(\App\Tag::class)->create(['name' => 'testTag']);
        $this->expectException(\Illuminate\Database\QueryException::class);
        factory(\App\Tag::class)->create(['name' => 'testTag']);
    }

    public function testBelongsToManyTasks()
    {
        // Create a task
        $task = factory(\App\Task::class)->create([
            'user_id' => $this->user->id,
            'column_id' => $this->column->id,
            'active' => true,
            'deleted_at' => null
        ]);

        // Create active and inactive tags
        $activeTags = factory(\App\Tag::class, self::numberOfActiveTags)->create(['active' => true, 'deleted_at' => null]);
        $inactiveTags = factory(\App\Tag::class, self::numberOfInactiveTags)->create(['active' => false, 'deleted_at' => null]);

        // Attach tags to the task
        $task->tags()->attach($activeTags, ['created_at' => now(), 'updated_at' => now()]);
        $task->tags()->attach($inactiveTags, ['created_at' => now(), 'updated_at' => now()]);

        // Assert that the task active tags in database and the collection are the same
        $this->assertEqualsCanonicalizing(
            $task->tags()->active()->pluck('tag_id')->toArray(),
            $activeTags->pluck("id")->toArray()
        );

        // Assert that task tags in database and collection are the same
        $allTags = collect(array_merge($activeTags->all(), $inactiveTags->all()));
        $this->assertEqualsCanonicalizing(
            $task->tags()->get()->pluck("id")->toArray(),
            $allTags->pluck('id')->toArray()
        );
    }
}
