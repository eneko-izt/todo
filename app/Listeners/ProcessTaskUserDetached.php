<?php

namespace App\Listeners;

use App\Events\TaskUserDetached;
use App\Http\Services\CacheService;
use Illuminate\Support\Facades\Log;

class ProcessTaskUserDetached
{
    public function handle(TaskUserDetached $event)
    {
        Log::info('User detached from task', [
            'task_id' => $event->task->id,
            'user_id' => $event->user->id
        ]);
        app(CacheService::class)->clearUserColumnTasksCache($event->task, $event->user);
    }
}
