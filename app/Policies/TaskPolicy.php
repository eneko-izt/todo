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
        return $this->isOwner($user, $task) && ($task->sharingUsers()->count() == 0);
    }

    public function shareTask(User $user, Task $task)
    {
        return $this->isOwner($user, $task);
    }

    public function uploadFile(User $user, Task $task)
    {
        return $this->isOwner($user, $task) || $task->sharingUsers->contains($user);
    }

    public function viewAllTasks(User $user)
    {
        return is_admin();
    }

    private function isOwner(User $user, Task $task)
    {
        return auth()->check() && auth()->user()->id == $task->user_id;
    }
}
