<?php

namespace App\Listeners;

use App\Events\TaskUserAttached;
use App\Http\Services\CacheService;
use Illuminate\Support\Facades\Log;

class ProcessTaskUserAttached
{
    public function handle(TaskUserAttached $event)
    {
        Log::info('User attached to task', [
            'task_id' => $event->task->id,
            'user_id' => $event->user->id,
        ]);
        app(CacheService::class)->clearUserColumnTasksCache($event->task, $event->user);
    }
}
