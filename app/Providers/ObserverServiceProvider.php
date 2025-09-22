<?php

namespace App\Providers;

use App\Column;
use App\Tag;
use App\Task;
use App\Observers\ColumnObserver;
use App\Observers\TagObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $columnObserver = new ColumnObserver();
        Column::observe($columnObserver);

        $tagObserver = new TagObserver();
        Tag::observe($tagObserver);

        $taskObserver = new TaskObserver();
        Task::observe($taskObserver);
    }
}
