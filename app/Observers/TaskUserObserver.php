<?php

namespace App\Observers;

use App\TaskUser;
use App\Http\Services\CacheService;

class TaskUserObserver
{
    /**
     * Handle the task user "created" event.
     *
     * @param  \App\TaskUser  $taskUser
     * @return void
     */
    public function created(TaskUser $taskUser)
    {
        app(CacheService::class)->clearUserColumnTasksCache($taskUser->task_id, $taskUser->user_id);
    }

    /**
     * Handle the task user "updated" event.
     *
     * @param  \App\TaskUser  $taskUser
     * @return void
     */
    public function updated(TaskUser $taskUser)
    {
    }

    /**
     * Handle the task user "deleted" event.
     *
     * @param  \App\TaskUser  $taskUser
     * @return void
     */
    public function deleted(TaskUser $taskUser)
    {
        app(CacheService::class)->clearUserColumnTasksCache($taskUser->task_id, $taskUser->user_id);
    }

    /**
     * Handle the task user "restored" event.
     *
     * @param  \App\TaskUser  $taskUser
     * @return void
     */
    public function restored(TaskUser $taskUser)
    {
    }

    /**
     * Handle the task user "force deleted" event.
     *
     * @param  \App\TaskUser  $taskUser
     * @return void
     */
    public function forceDeleted(TaskUser $taskUser)
    {
    }
}
