<?php

namespace App\Listeners;

use App\Events\AccrualOfPointsEvent;
use App\Models\Action;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AccrualOfPointsListener
{

    protected $action;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AccrualOfPointsEvent  $event
     * @return void
     */
    public function handle(AccrualOfPointsEvent $event)
    {
        $action = $this->action->where('title', $event->data['title'])->first();
        dd($action, 'lol');
    }

}
