<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ColumnPolicy
{
    use HandlesAuthorization;

    public function viewMenuColumn(User $user)
    {
        return $user->hasRoleName(('admin'));
    }
}
