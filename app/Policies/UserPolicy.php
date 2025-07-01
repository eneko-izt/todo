<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewMenuUser(User $user)
    {
        return $user->hasRoleName(('admin'));
    }
}
