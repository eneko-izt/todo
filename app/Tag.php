<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'colour', 'active'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    public function getUpperName()
    {
        return Str::upper($this->name);
    }
}
