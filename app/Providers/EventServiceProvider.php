<?php

namespace App\Providers;

use App\Events\TaskUserAttached;
use App\Events\TaskUserDetached;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ProcessTaskUserAttached;
use App\Listeners\ProcessTaskUserDetached;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskUserAttached::class => [
            ProcessTaskUserAttached::class,
        ],
        TaskUserDetached::class => [
            ProcessTaskUserDetached::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
