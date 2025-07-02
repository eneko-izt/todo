<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewUser()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function editUser()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function createUser()
    {
        return auth()->user()->hasRoleName(('admin'));
    }
}
