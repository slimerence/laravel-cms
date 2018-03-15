<?php

namespace App\Listeners\Contact;

use App\Events\Contact\LeadReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadReceivedEventListener
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
     * @param  LeadReceived  $event
     * @return void
     */
    public function handle(LeadReceived $event)
    {
        //
    }
}
