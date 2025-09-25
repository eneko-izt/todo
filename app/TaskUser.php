<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskUser extends Pivot
{
    protected $table = 'task_user';
}
