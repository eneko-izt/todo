<?php

namespace App;
//TODO: erabiltzen ez direnak ezabatu
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

    //TODO: scope hau modelo ezberdinetan erabiltzen da, beraz, Trait-ean jarri dezakezu
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
