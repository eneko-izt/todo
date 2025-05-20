<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    // protected $fillable = ['name', 'colour', 'active'];
    // TODO: segurtasunagatik hobe da eremuak banan banan zerrendatzea fillable propieatateetan. Guarded hutsa erabiltzean errezagoa da tranpeatzea

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getUpperName()
    {
        return Str::upper($this->name);
    }
}
