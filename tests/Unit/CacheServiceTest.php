<?php

namespace Tests\Feature;

use App\Tag;
use App\Column;
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
        $this->assertEquals(Cache::get('active_columns')->pluck('id'), $cacheService->activeColumns()->pluck('id'));

        Cache::forget('active_columns');
        $cacheService->activeColumns();

        $this->assertCount(2, $cacheService->activeColumns());
        $this->assertEquals(Cache::get('active_columns')->pluck('id'), $cacheService->activeColumns()->pluck('id'));
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
        $this->assertEquals(Cache::get('active_tags')->pluck('id'), $cacheService->activeTags()->pluck('id'));

        Cache::forget('active_tags');
        $cacheService->activeTags();

        $this->assertCount(2, $cacheService->activeTags());
        $this->assertEquals(Cache::get('active_tags')->pluck('id'), $cacheService->activeTags()->pluck('id'));
    }
}