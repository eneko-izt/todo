<?php

namespace App\Observers;

use App\Task;
use App\Http\Services\CacheService;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        if ($task->active) {
            app(CacheService::class)->clearTaskCacheWhenTaskCrudChange($task);
        }
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->wasChanged('active') || (!$task->wasChanged('active') && $task->active)) {
            app(CacheService::class)->clearTaskCacheWhenTaskCrudChange($task);
        }
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        if ($task->active) {
            app(CacheService::class)->clearTaskCacheWhenTaskCrudChange($task);
        }
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        if ($task->active) {
            app(CacheService::class)->clearTaskCacheWhenTaskCrudChange($task);
        }
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
