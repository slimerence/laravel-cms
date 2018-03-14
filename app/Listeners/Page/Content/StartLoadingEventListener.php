<?php

namespace App\Listeners\Page\Content;

use App\Events\Page\Content\StartLoading;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartLoadingEventListener
{
    /**
     * StartLoadingEventListener constructor.
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
//        dump($event->dataForView);
    }
}
