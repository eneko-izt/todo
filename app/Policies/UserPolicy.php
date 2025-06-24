<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewMenuUser()
    {
        return auth()->user()->hasRoleName(('admin'));
    }
}
