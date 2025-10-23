<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    use Traits\BasicTrait;

    protected $fillable = ['name', 'colour', 'active'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }
}
