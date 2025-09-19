<?php

namespace App\Observers;

use App\Column;
use App\Http\Services\CacheService;

class ColumnObserver
{
    /**
     * Handle the column "created" event.
     *
     * @param  \App\Column  $column
     * @return void
     */
    public function created(Column $column)
    {
        //
    }

    /**
     * Handle the column "updated" event.
     *
     * @param  \App\Column  $column
     * @return void
     */
    public function updated(Column $column)
    {
        if ($column->wasChanged('active')) {
            // Clear cache for active columns
            app(CacheService::class)->clearActiveColumnsCache();
        }
    }

    /**
     * Handle the column "deleted" event.
     *
     * @param  \App\Column  $column
     * @return void
     */
    public function deleted(Column $column)
    {
        if ($column->active) {
            // Clear cache for active columns
            app(CacheService::class)->clearActiveColumnsCache();
        }
    }

    /**
     * Handle the column "restored" event.
     *
     * @param  \App\Column  $column
     * @return void
     */
    public function restored(Column $column)
    {
        if ($column->active) {
            // Clear cache for active columns
            app(CacheService::class)->clearActiveColumnsCache();
        }
    }

    /**
     * Handle the column "force deleted" event.
     *
     * @param  \App\Column  $column
     * @return void
     */
    public function forceDeleted(Column $column)
    {
        //
    }
}
