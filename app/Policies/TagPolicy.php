<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewTag()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function editTag()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function createTag()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function deleteTag()
    {
        return auth()->user()->hasRoleName(('admin'));
    }
}
