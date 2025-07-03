<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ColumnPolicy
{
    use HandlesAuthorization;

    public function viewColumn()
    {
        return is_admin();
    }

    public function editColumn()
    {
        return is_admin();
    }

    public function createColumn()
    {
        return is_admin();
    }

    public function deleteColumn()
    {
        return is_admin();
    }
}
