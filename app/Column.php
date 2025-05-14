<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function name()
    {
        return Str::upper($this->name);
    }
}
