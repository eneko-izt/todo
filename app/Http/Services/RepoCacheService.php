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
            return $this->fetchColumnsFromDatabase();
        });
    }

    public function userViewableTasks($user, $column)
    {
        return Cache::remember("user_{$user->id}_column_{$column->id}_viewable_tasks", 60 * 24, function () use ($user, $column) {
            return $this->fetchUserColumnTasksFromDatabase($user, $column);
        });
    }

    private function fetchColumnsFromDatabase()
    {
        return \App\Column::active()->get();
    }

    private function fetchUserColumnTasksFromDatabase($user, $column)
    {
        $sharedTasks = $user->sharedTasks()
            ->where('tasks.column_id', $column->id)
            ->where('tasks.user_id', '!=', $user->id)
            ->active()
            ->select('tasks.*')
            ->orderBy('order');

        return $column->activeTasks()->union($sharedTasks)->distinct('tasks.id')->orderBy('order')->get();
    }
}