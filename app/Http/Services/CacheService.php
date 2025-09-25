<?php

namespace App\Http\Services;

use App\Tag;
use App\Column;
use App\Task;
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
    }

    public function clearActiveTagsCache()
    {
        Cache::forget('active_tags');
    }

    public function clearTaskCacheWhenTaskCrudChange($task)
    {
        $task->refresh();
        $users = array($task->user_id, ...$task->sharingUsers->pluck('id')->toArray());
        $column = $task->column;
        foreach ($users as $userId) {
            Cache::forget("user_{$userId}_column_{$column->id}_viewable_tasks");
        }
    }

    public function clearUserColumnTasksCache($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        Cache::forget("user_{$userId}_column_{$task->column_id}_viewable_tasks");
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