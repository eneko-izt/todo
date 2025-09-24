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
        return $this->belongsToMany(User::class)
            ->using(\App\TaskUser::class)
            ->withTimestamps();
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
