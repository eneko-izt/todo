<?php

namespace App\Observers;

use App\Tag;
use App\Http\Services\CacheService;

class TagObserver
{
    /**
     * Handle the tag "created" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        if ($tag->active) {
            app(CacheService::class)->clearActiveTagsCache();
        }
    }

    /**
     * Handle the tag "updated" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        if ($tag->wasChanged('active') || (!$tag->wasChanged('active') && $tag->active)) {
            app(CacheService::class)->clearActiveTagsCache();
        }
    }

    /**
     * Handle the tag "deleted" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        if ($tag->active) {
            app(CacheService::class)->clearActiveTagsCache();
        }
    }

    /**
     * Handle the tag "restored" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function restored(Tag $tag)
    {
        if ($tag->active) {
            app(CacheService::class)->clearActiveTagsCache();
        }
    }

    /**
     * Handle the tag "force deleted" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function forceDeleted(Tag $tag)
    {
        //
    }
}
