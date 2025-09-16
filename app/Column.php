<?php

namespace App;

use App\Http\Services\RepoCacheService;
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

    public function activeTasks()
    {
        return $this->tasks()->where('user_id', auth()->user()->id)->active()->orderBy('order');
    }

    public function viewableTasks()
    {
        $repoCacheService = app(RepoCacheService::class);
        return $repoCacheService->userViewableTasks(auth()->user(), $this);
    }
}
