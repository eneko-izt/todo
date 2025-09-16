<?php

namespace Tests\Feature;

use App\Task;
use App\Tag;
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

    public function test_tags_are_cached()
    {
        $repoCacheService = new RepoCacheService();

        factory(Tag::class)->create(['active' => true]);
        $this->assertCount(1, $repoCacheService->activeTags());

        factory(Tag::class)->create(['active' => true]);
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(1, $repoCacheService->activeTags());

        Cache::flush();
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(2, $repoCacheService->activeTags());
    }

    public function test_tags_inactive_are_cached()
    {
        $repoCacheService = new RepoCacheService();

        factory(Tag::class)->create(['active' => true]);
        $inactiveTag = factory(Tag::class)->create(['active' => false]);
        $this->assertCount(2, Tag::all());
        $this->assertCount(1, Tag::active()->get());
        $this->assertCount(1, $repoCacheService->activeTags());

        $inactiveTag->active = true;
        $inactiveTag->save();
        $this->assertCount(2, Tag::all());
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(1, $repoCacheService->activeTags());

        Cache::flush();
        $this->assertCount(2, Tag::all());
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(2, $repoCacheService->activeTags());
    }

    public function test_tags_deleted_are_cached()
    {
        $repoCacheService = new RepoCacheService();

        factory(Tag::class)->create(['active' => true]);
        $deleteStatusChangingTag = factory(Tag::class)->create(['active' => true]);
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(2, $repoCacheService->activeTags());

        $deleteStatusChangingTag->Delete();
        $this->assertCount(1, Tag::active()->get());
        $this->assertCount(2, $repoCacheService->activeTags());

        Cache::flush();
        $this->assertCount(1, Tag::active()->get());
        $this->assertCount(1, $repoCacheService->activeTags());

        $deleteStatusChangingTag->Restore();
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(1, $repoCacheService->activeTags());

        Cache::flush();
        $this->assertCount(2, Tag::active()->get());
        $this->assertCount(2, $repoCacheService->activeTags());
    }
}