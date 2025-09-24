<?php

namespace Tests\Feature;

use App\Tag;
use App\Column;
use App\Task;
use App\User;
use Tests\TestCase;
use App\Http\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Run the test with vendor/bin/phpunit in attached shell
     * OR
     * vendor/bin/phpunit --coverage-html tests/coverage for coverage report
     */

    public function test_columns_are_cached()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();

        $this->assertTrue(Cache::has('active_columns'));
        $cachedColumns = Cache::get('active_columns');
        $this->assertCount(1, $cachedColumns);
        $this->assertContains($column->id, $cachedColumns->pluck('id'));

        $anotherColumn = factory(Column::class)->create(['active' => true]);
        Cache::forget('active_columns');
        $cacheService->activeColumns();

        $cachedColumns = Cache::get('active_columns');
        $this->assertCount(2, $cachedColumns);
        $this->assertContains($column->id, $cachedColumns->pluck('id'));
        $this->assertContains($anotherColumn->id, $cachedColumns->pluck('id'));
    }

    public function test_active_columns_equals_cache()
    {
        $cacheService = new CacheService();

        factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(Cache::get('active_columns')->pluck('id'), $cacheService->activeColumns()->pluck('id'));

        factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(Cache::get('active_columns')->pluck('id'), $cacheService->activeColumns()->pluck('id'));

        Cache::forget('active_columns');
        $cacheService->activeColumns();

        $this->assertCount(2, $cacheService->activeColumns());
        $this->assertEquals(Cache::get('active_columns')->pluck('id'), $cacheService->activeColumns()->pluck('id'));
    }

    public function test_columns_cache_refresh_after_create()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(1, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));

        $anotherColumn = factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(2, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));
        $this->assertContains($anotherColumn->id, Cache::get('active_columns')->pluck('id'));
    }

    public function test_columns_cache_refresh_after_update()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create(['active' => true, 'name' => 'Initial Name']);
        $cacheService->activeColumns();
        $this->assertEquals(1, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));

        $column->update(['name' => 'Updated Name']);
        $cacheService->activeColumns();
        $this->assertContains($column->name, Cache::get('active_columns')->pluck('name'));

        $column->update(['active' => false]);
        $cacheService->activeColumns();
        $this->assertEquals(0, Cache::get('active_columns')->count());

        $column->update(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(1, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));
    }

    public function test_columns_cache_refresh_after_delete_restore()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create(['active' => true]);
        $cacheService->activeColumns();
        $this->assertEquals(1, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));

        $column->delete();
        $cacheService->activeColumns();
        $this->assertEquals(0, Cache::get('active_columns')->count());

        $column->restore();
        $cacheService->activeColumns();
        $this->assertEquals(1, Cache::get('active_columns')->count());
        $this->assertContains($column->id, Cache::get('active_columns')->pluck('id'));
    }

    public function test_tags_are_cached()
    {
        $cacheService = new CacheService();

        $tag = factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertTrue(Cache::has('active_tags'));
        $cachedColumns = Cache::get('active_tags');
        $this->assertCount(1, $cachedColumns);
        $this->assertContains($tag->id, $cachedColumns->pluck('id'));

        $anotherTag = factory(Tag::class)->create(['active' => true]);
        Cache::forget('active_tags');
        $cacheService->activeTags();

        $cachedTags = Cache::get('active_tags');
        $this->assertCount(2, $cachedTags);
        $this->assertContains($tag->id, $cachedTags->pluck('id'));
        $this->assertContains($anotherTag->id, $cachedTags->pluck('id'));
    }

    public function test_active_tags_equals_cached()
    {
        $cacheService = new CacheService();

        factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(Cache::get('active_tags')->pluck('id'), $cacheService->activeTags()->pluck('id'));

        factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(Cache::get('active_tags')->pluck('id'), $cacheService->activeTags()->pluck('id'));

        Cache::forget('active_tags');
        $cacheService->activeTags();
        $this->assertCount(2, $cacheService->activeTags());
        $this->assertEquals(Cache::get('active_tags')->pluck('id'), $cacheService->activeTags()->pluck('id'));
    }

    public function test_tags_cache_refresh_after_create()
    {
        $cacheService = new CacheService();

        $tag = factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(1, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));

        $anotherTag = factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(2, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));
        $this->assertContains($anotherTag->id, Cache::get('active_tags')->pluck('id'));
    }

    public function test_tags_cache_refresh_after_update()
    {
        $cacheService = new CacheService();

        $tag = factory(Tag::class)->create(['active' => true, 'name' => 'Initial Name']);
        $cacheService->activeTags();
        $this->assertEquals(1, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));

        $tag->update(['name' => 'Updated Name']);
        $cacheService->activeTags();
        $this->assertContains($tag->name, Cache::get('active_tags')->pluck('name'));

        $tag->update(['active' => false]);
        $cacheService->activeTags();
        $this->assertEquals(0, Cache::get('active_tags')->count());

        $tag->update(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(1, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));
    }

    public function test_tags_cache_refresh_after_delete_restore()
    {
        $cacheService = new CacheService();

        $tag = factory(Tag::class)->create(['active' => true]);
        $cacheService->activeTags();
        $this->assertEquals(1, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));

        $tag->delete();
        $cacheService->activeTags();
        $this->assertEquals(0, Cache::get('active_tags')->count());

        $tag->restore();
        $cacheService->activeTags();
        $this->assertEquals(1, Cache::get('active_tags')->count());
        $this->assertContains($tag->id, Cache::get('active_tags')->pluck('id'));
    }

    public function test_user_tasks_are_cached()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertTrue(Cache::has("user_{$userOwner->id}_column_{$column->id}_viewable_tasks"));
        $cachedTasks = Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks");
        $this->assertCount(1, $cachedTasks);
        $this->assertContains($task->id, $cachedTasks->pluck('id'));

        $anotherTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);
        Cache::forget("user_{$userOwner->id}_column_{$column->id}_viewable_tasks");
        $cacheService->userViewableTasks($userOwner, $column);

        $cachedColumns = Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks");
        $this->assertCount(2, $cachedColumns);
        $this->assertContains($column->id, $cachedColumns->pluck('id'));
        $this->assertContains($anotherTask->id, $cachedColumns->pluck('id'));
    }

    public function test_user_tasks_equals_cache()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);

        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'), $cacheService->userViewableTasks($userOwner, $column)->pluck('id'));

        $anotherTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);
        Cache::forget("user_{$userOwner->id}_column_{$column->id}_viewable_tasks");
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertCount(2, $cacheService->userViewableTasks($userOwner, $column));
        $this->assertEquals(Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'), $cacheService->userViewableTasks($userOwner, $column)->pluck('id'));
    }

    public function test_tasks_cache_refresh_after_create()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $anotherTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id]);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(2, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));
        $this->assertContains($anotherTask->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));
    }

    public function test_tasks_cache_refresh_after_update()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id, 'text' => 'Initial text']);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $task->update(['text' => 'Updated text']);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertContains($task->text, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('text'));

        $task->update(['active' => false]);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(0, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());

        $task->update(['active' => true]);
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));
    }

    public function test_tasks_cache_refresh_after_delete_restore()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id, 'text' => 'Initial text']);

        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $task->delete();
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(0, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());

        $task->restore();
        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));
    }

    public function test_task_cache_refresh_after_sharing_unsharing()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id, 'text' => 'Initial text']);

        $cacheService->userViewableTasks($userOwner, $column);
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $task->sharingUsers()->attach($anotherUser->id);

        // check nothing changed for owner user task caching
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        // check new user task is not cached yet
        $this->assertFalse(Cache::has("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks"));

        $cacheService->userViewableTasks($anotherUser, $column);

        // check new user task is also cached
        $this->assertEquals(1, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $task->sharingUsers()->detach($anotherUser->id);
        $cacheService->userViewableTasks($anotherUser, $column);

        // check nothing changed for owner user task caching
        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        // check new user task is not shared anymore
        $this->assertEquals(0, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->count());
    }

    public function test_tasks_cache_refresh_after_update_with_sharing_user()
    {
        $cacheService = new CacheService();

        $column = factory(Column::class)->create();
        $userOwner = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $userOwner->id, 'text' => 'Initial text']);
        $task->sharingUsers()->attach($anotherUser->id);

        $cacheService->userViewableTasks($userOwner, $column);
        $cacheService->userViewableTasks($anotherUser, $column);

        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $this->assertEquals(1, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $task->update(['text' => 'Updated text']);
        $cacheService->userViewableTasks($userOwner, $column);
        $cacheService->userViewableTasks($anotherUser, $column);

        $this->assertContains($task->text, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('text'));
        $this->assertContains($task->text, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->pluck('text'));

        $task->update(['active' => false]);
        $cacheService->userViewableTasks($userOwner, $column);
        $cacheService->userViewableTasks($anotherUser, $column);

        $this->assertEquals(0, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertEquals(0, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->count());

        $task->update(['active' => true]);
        $cacheService->userViewableTasks($userOwner, $column);
        $cacheService->userViewableTasks($anotherUser, $column);

        $this->assertEquals(1, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$userOwner->id}_column_{$column->id}_viewable_tasks")->pluck('id'));

        $this->assertEquals(1, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->count());
        $this->assertContains($task->id, Cache::get("user_{$anotherUser->id}_column_{$column->id}_viewable_tasks")->pluck('id'));
    }
}