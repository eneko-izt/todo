<?php

namespace App;

use App\Http\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use SoftDeletes;
    use Traits\BasicTrait;

    protected $fillable = ['name', 'colour', 'active'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activeTasks($userId)
    {
        return $this->tasks()->where('user_id', $userId)->active()->orderBy('order');
    }

    public function viewableTasks()
    {
        $cacheService = app(CacheService::class);
        return $cacheService->userViewableTasks(auth()->user(), $this);
    }
}
