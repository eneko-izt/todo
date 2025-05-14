<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use SoftDeletes;
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function name()
    {
        return Str::upper($this->name);
    }
}
