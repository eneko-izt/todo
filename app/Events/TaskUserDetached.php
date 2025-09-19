<?php

namespace App\Events;

use App\Task;
use Illuminate\Queue\SerializesModels;

class TaskUserDetached
{
    use SerializesModels;

    public $task;
    public $user;

    public function __construct(Task $task, $user)
    {
        $this->task = $task;
        $this->user = $user;
    }
}
