<?php

namespace App\Http\Services;

use App\User;
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

    public function userViewableTasks($user, $column)
    {
        return Cache::remember("user_{$user->id}_column_{$column->id}_viewable_tasks", now()->addHours(1), function () use ($user, $column) {
            return $this->fetchUserColumnTasksFromDatabase($user, $column);
        });
    }

    public function clearActiveColumnsCache()
    {
        Cache::forget('active_columns');
        $this->activeColumns();
    }

    public function checkAndClearTaskCacheWhenTaskCrudChange($task)
    {
        $users = array($task->user_id, ...$task->sharingUsers()->pluck('users.id')->toArray());
        $column = $task->column;
        foreach ($users as $userId) {
            Cache::forget("user_{$userId}_column_{$column->id}_viewable_tasks");
        }

        // Preload cache again
        $user = $task->user;
        if ($user) {
            $this->userViewableTasks($user, $column);
        }
        foreach ($task->sharingUsers as $user) {
            $this->userViewableTasks($user, $column);
        }

        Cache::forget("user_{$task->user_id}_column_{$column->id}_viewable_tasks");
    }

    public function clearUserColumnTasksCache($task, $user)
    {
        Cache::forget("user_{$user->id}_column_{$task->column_id}_viewable_tasks");
        $this->userViewableTasks($user, $task->column);
    }

    private function fetchUserColumnTasksFromDatabase($user, $column)
    {
        $sharedTasks = $user->sharedTasks()
            ->where('tasks.column_id', $column->id)
            ->where('tasks.user_id', '!=', $user->id)
            ->active()
            ->select('tasks.*')
            ->orderBy('order');

        return $column->activeTasks($user->id)->union($sharedTasks)->distinct('tasks.id')->orderBy('order')->get();
    }
}