<?php

namespace App\Listeners\Contact;

use App\Events\Contact\LeadReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LeadReceivedEventListener
{
    /**
     * 构造函数
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
        Log::info('Lead',['to_admin'=>$event->adminEmail]);
        Log::info('Lead',['to_user'=>$event->lead->email]);
    }
}
