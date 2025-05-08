<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
