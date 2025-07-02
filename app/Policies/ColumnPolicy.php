<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class ColumnPolicy
{
    use HandlesAuthorization;

    public function viewColumn()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function editColumn()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function createColumn()
    {
        return auth()->user()->hasRoleName(('admin'));
    }

    public function deleteColumn()
    {
        return auth()->user()->hasRoleName(('admin'));
    }
}
