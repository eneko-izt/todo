<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewTag()
    {
        return is_admin();
    }

    public function viewTrash()
    {
        return is_admin();
    }

    public function editTag()
    {
        return is_admin();
    }

    public function createTag()
    {
        return is_admin();
    }

    public function deleteTag()
    {
        return is_admin();
    }
}
