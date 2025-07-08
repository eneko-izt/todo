<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewUser()
    {
        return is_admin();
    }

    public function editUser()
    {
        return is_admin();
    }

    public function createUser()
    {
        return is_admin();
    }
}
