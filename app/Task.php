<?php

namespace App;

use App\Events\TaskUserAttached;
use App\Events\TaskUserDetached;
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
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function attachSharingUser($userId)
    {
        $this->sharingUsers()->attach($userId);
        event(new TaskUserAttached($this, $userId));
    }

    public function detachSharingUser($userId)
    {
        $this->sharingUsers()->detach($userId);
        event(new TaskUserDetached($this, $userId));
    }

    public function shareableUsers()
    {
        return User::active()
                ->where('id', '!=', auth()->id())
                ->whereNotIn('id', $this->sharingUsers()->pluck('users.id'))
                ->get();    
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
