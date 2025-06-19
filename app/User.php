<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'active', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->whereNull('role_user.deleted_at')->withPivot(['deleted_at']);
    }

    public function roleswithtrashed()
    {
        return $this->belongsToMany(Role::class)->withPivot(['deleted_at']);
    }

    public function hasRoleName($roleName)
    {
        $roles = $this->roles->where('name', $roleName);
        if ($roles->count() > 0)
        {
            return $this->roles->where('name', $roleName)->first()->pivot->deleted_at === null;
        }
        return false;
    }

    public function hasRoleId($roleId)
    {
        $roles = $this->roles->where('id', $roleId);
        if ($roles->count() > 0)
        {
            return $this->roles->where('id', $roleId)->first()->pivot->deleted_at === null;
        }
        return false;
    }
}
