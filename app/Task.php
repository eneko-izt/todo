<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    use Traits\BasicTrait;

    protected $fillable = ['text', 'order', 'user_id', 'column_id', 'active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function sharingUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')->whereNull('task_user.deleted_at')->withPivot(['deleted_at'])->withTimestamps();
    }

    public function sharingUsersWithTrashed()
    {
        return $this->belongsToMany(User::class, 'task_user')->withPivot(['deleted_at'])->withTimestamps();
    }

}
