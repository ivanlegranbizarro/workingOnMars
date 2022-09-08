<?php

namespace App\Listeners;

use App\Models\Candidate;
use App\Events\NewJobRegisteredEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewJobRegisteredNotification;

class NewJobRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewJobRegisteredEvent $event)
    {
        $candidates = Candidate::where('subscribed', true)->get();
        Notification::send($candidates, new NewJobRegisteredNotification($event->job));
    }
}