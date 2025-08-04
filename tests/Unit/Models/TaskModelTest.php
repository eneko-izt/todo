<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Task Model: Run the test with vendor/bin/phpunit in attached shell
     * 
     * Requirements:
     *      -> Tasks can be assigned to multiple tags and active trait functionality must work correctly
     * 
     */

    private const numberOfActiveTasks = 10;
    private const numberOfInactiveTasks = 7;

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

    public function testBelongsToManyTasks()
    {
        $tag = factory(\App\Tag::class)->create([
            'active' => true,
            'deleted_at' => null
        ]);

        $activeTasks = factory(\App\Task::class, self::numberOfActiveTasks)->create([
            'user_id' => $this->user->id,
            'column_id' => $this->column->id,
            'active' => true,
            'deleted_at' => null
        ]);

        $inactiveTasks = factory(\App\Task::class, self::numberOfInactiveTasks)->create([
            'user_id' => $this->user->id,
            'column_id' => $this->column->id,
            'active' => false,
            'deleted_at' => null
        ]);

        $tag->tasks()->attach($activeTasks, ['created_at' => now(), 'updated_at' => now()]);
        $tag->tasks()->attach($inactiveTasks, ['created_at' => now(), 'updated_at' => now()]);

        $this->assertEqualsCanonicalizing(
            $activeTasks->pluck('id')->toArray(),
            $tag->tasks()->active()->get()->pluck("id")->toArray()
        );

        $allTasks = collect(array_merge($activeTasks->all(), $inactiveTasks->all()));

        $this->assertEqualsCanonicalizing(
            $allTasks->pluck('id')->toArray(),
            $tag->tasks()->get()->pluck("id")->toArray()
        );
    }
}
