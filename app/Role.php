<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class)->whereNull('role_user.deleted_at')->withPivot(['deleted_at'])->withTimestamps();
    }

    public function usersWithTrashed()
    {
        return $this->belongsToMany(User::class)->withPivot(['deleted_at'])->withTimestamps();
    }
}
