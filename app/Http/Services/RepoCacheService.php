<?php

namespace App\Http\Services;

use App\Tag;
use App\User;
use App\Column;
use Illuminate\Support\Facades\Cache;

class RepoCacheService
{
    public function activeColumns()
    {
        return Cache::remember('active_columns', 60 * 24, function () {
            return \App\Column::active()->get();
        });
    }

    public function activeTags()
    {
        return Cache::remember('active_tags', 60 * 24, function () {
            return Tag::active()->get();
        });
    }
}