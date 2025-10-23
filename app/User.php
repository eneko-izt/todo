<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Traits\BasicTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'email',
        'password',
        'language',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeUsersNotLoggedIn($query)
    {
        return $query->where('id', '!=', auth()->id());
    }

    public function scopeUsersNotSharingTask($query, $task)
    {
        return $query->whereNotIn('id', $task->sharingUsers()->pluck('users.id'));
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->whereNull('role_user.deleted_at')->withPivot(['deleted_at'])->withTimestamps();
    }

    public function rolesWithTrashed()
    {
        return $this->belongsToMany(Role::class)->withPivot(['deleted_at'])->withTimestamps();
    }

    public function hasRoleName($roleName)
    {
        $roles = $this->roles->where('name', $roleName);
        if ($roles->count() > 0) {
            return $this->roles->where('name', $roleName)->first()->pivot->deleted_at === null;
        }

        return false;
    }

    public function hasRoleId($roleId)
    {
        $roles = $this->roles->where('id', $roleId);
        if ($roles->count() > 0) {
            return $this->roles->where('id', $roleId)->first()->pivot->deleted_at === null;
        }

        return false;
    }

    public function sharedTasks()
    {
        return $this->belongsToMany(Task::class)
            ->using(\App\TaskUser::class)
            ->withTimestamps();
    }
}
