<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewMenuTag(User $user)
    {
        return $user->name == 'admin';
    }
}
