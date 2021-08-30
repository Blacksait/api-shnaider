<?php

namespace App\Providers;

use App\Events\AccrualOfPointsEvent;
use App\Listeners\AccrualOfPointsListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AccrualOfPointsEvent::class => [
            AccrualOfPointsListener::class
        ]
    ];
}
