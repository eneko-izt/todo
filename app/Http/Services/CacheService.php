<?php

namespace App\Http\Services;

use App\Tag;
use App\Column;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function activeColumns()
    {
        return Cache::remember('active_columns', now()->addDays(1), function () {
            return Column::active()->get();
        });
    }

    public function activeTags()
    {
        return Cache::remember('active_tags', now()->addDays(1), function () {
            return Tag::active()->get();
        });
    }
}