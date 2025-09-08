<?php

namespace App\Policies;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewTask()
    {
        return false;
    }

    public function editTask(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    public function createTask()
    {
        return false;
    }

    public function deleteTask(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    public function shareTask(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    public function uploadFile(User $user, Task $task)
    {
        // TODO: change this to allow shared users to upload files too
        return $this->isOwner($user, $task);
    }

    private function isOwner(User $user, Task $task)
    {
        return auth()->check() && auth()->user()->id == $task->user_id;
    }
}
