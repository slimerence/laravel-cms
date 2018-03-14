<?php

namespace App\Listeners\Page\Hook;

use App\Events\Page\Hook\StartLoading;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartLoadingEventListener
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
     * @param  StartLoading  $event
     * @return void
     */
    public function handle(StartLoading $event)
    {
        //
    }
}
