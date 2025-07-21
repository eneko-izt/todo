<?php

namespace App;

//TODO: erabiltzen ez direnak ezabatu
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
