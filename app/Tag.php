<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    use Traits\StringTrait;

    protected $fillable = ['name', 'colour', 'active'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
