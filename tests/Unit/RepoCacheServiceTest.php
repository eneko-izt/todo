<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Http\Services\RepoCacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepoCacheServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Run the test with vendor/bin/phpunit in attached shell
     */

    public function test_columns_are_cached()
    {
        $repoCacheService = new RepoCacheService();
        $columns = factory(Column::class)->create(['active' => true]);
        $this->assertCount(1, $repoCacheService->activeColumns());

        $columns = factory(Column::class)->create(['active' => true]);
        $this->assertCount(2, Column::active()->get());
        $this->assertCount(1, $repoCacheService->activeColumns());

        Cache::flush();
        $this->assertCount(2, $repoCacheService->activeColumns());
    }

    public function test_user_viewable_tasks_cached()
    {
        $repoCacheService = new RepoCacheService();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $column = factory(Column::class)->create(['active' => true]);

        factory(Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id, 'active' => true]);
        $this->assertCount(1, $repoCacheService->userViewableTasks($user, $column));

        $tasks = factory(Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id, 'active' => true]);
        $this->assertCount(2, Task::where('user_id', $user->id)->where('column_id', $column->id)->active()->get());
        $this->assertCount(1, $repoCacheService->userViewableTasks($user, $column));

        Cache::flush();
        $this->assertCount(2, Task::where('user_id', $user->id)->where('column_id', $column->id)->active()->get());
        $this->assertCount(2, $repoCacheService->userViewableTasks($user, $column));
    }

    public function test_user_viewable_shared_tasks_cached()
    {
        $repoCacheService = new RepoCacheService();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $column = factory(Column::class)->create(['active' => true]);

        factory(Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id, 'active' => true]);
        $this->assertCount(1, $repoCacheService->userViewableTasks($user, $column));

        $otherUser = factory(User::class)->create();
        $sharedTask = factory(Task::class)->create(['user_id' => $otherUser->id, 'column_id' => $column->id, 'active' => true]);
        $sharedTask->sharingUsers()->attach($user->id);

        $this->assertCount(2, Task::where('column_id', $column->id)->active()->get());
        $this->assertCount(1, $repoCacheService->userViewableTasks($user, $column));

        Cache::flush();
        $this->assertCount(2, Task::where('column_id', $column->id)->active()->get());
        $this->assertCount(2, $repoCacheService->userViewableTasks($user, $column));
    }
}