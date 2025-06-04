<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
}
