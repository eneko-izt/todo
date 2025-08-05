<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColumnModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Column model: Run the test with vendor/bin/phpunit in attached shell
     * 
     * - Test active scope trait
     * - Test hasMany relationship
     * 
     */

    private $defaultRole = null;
    private $defaultColumn = null;
    private $user1 = null;
    private $user2 = null;

    private const numberOfActiveTasks = 5;
    private const numberOfInactiveTasks = 3;

    public function setUp(): void
    {
        parent::setUp();

        $this->defaultRole = factory(\App\Role::class)->create(['name' => 'user']);
        $this->defaultColumn = factory(\App\Column::class)->create(['active' => true, 'deleted_at' => null]);

        $this->user1 = factory(\App\User::class)->state('active')->create();
        $this->user1->roles()->attach($this->defaultRole);

        $this->user2 = factory(\App\User::class)->state('active')->create();
        $this->user2->roles()->attach($this->defaultRole);
    }

    public function testActiveScope()
    {
        $activeTasks = $this->createTask(self::numberOfActiveTasks, true, $this->user1, $this->defaultColumn);
        $inactiveTasks = $this->createTask(self::numberOfInactiveTasks, false, $this->user1, $this->defaultColumn);

        $user2ActiveTasks = $this->createTask(self::numberOfActiveTasks * 2, true, $this->user2, $this->defaultColumn);
        $user2InactiveTasks = $this->createTask(self::numberOfInactiveTasks * 2, false, $this->user2, $this->defaultColumn);

        $this->actingAs($this->user1);
        $this->assertCount(self::numberOfActiveTasks, $this->defaultColumn->activeTasks()->get());
        $this->assertEqualsCanonicalizing($activeTasks->pluck('id')->toArray(), $this->defaultColumn->activeTasks()->pluck('id')->toArray());
    }

    public function testHasMany()
    {
        $activeTasks = $this->createTask(self::numberOfActiveTasks, true, $this->user1, $this->defaultColumn);
        $inactiveTasks = $this->createTask(self::numberOfInactiveTasks, false, $this->user1, $this->defaultColumn);

        $user2ActiveTasks = $this->createTask(self::numberOfActiveTasks * 2, true, $this->user2, $this->defaultColumn);
        $user2InactiveTasks = $this->createTask(self::numberOfInactiveTasks * 2, false, $this->user2, $this->defaultColumn);

        $this->actingAs($this->user1);
        $totalTasks = self::numberOfActiveTasks + self::numberOfInactiveTasks + (self::numberOfActiveTasks * 2) + (self::numberOfInactiveTasks * 2);
        $this->assertCount($totalTasks, $this->defaultColumn->tasks()->get());

        $allTasks = collect(array_merge($activeTasks->all(), $inactiveTasks->all(), $user2ActiveTasks->all(), $user2InactiveTasks->all()));
        $this->assertEqualsCanonicalizing($allTasks->pluck('id')->toArray(), $this->defaultColumn->tasks()->pluck('id')->toArray());
    }

    private function createTask($numberOfRows, $active, $user, $column)
    {
        return factory(\App\Task::class, $numberOfRows)->create([
            'user_id' => $user->id,
            'column_id' => $column->id,
            'active' => $active,
            'deleted_at' => null
        ]);
    }
}
