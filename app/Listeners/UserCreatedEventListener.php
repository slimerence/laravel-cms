<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\User\Created\ToCustomer;
use Carbon\Carbon;
use App\Models\Utils\UserGroup;
use App\Jobs\User\Created\ToAdmin;

class UserCreatedEventListener implements ShouldQueue
{
    /**
     * UserCreatedEventListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return boolean
     */
    public function handle(UserCreated $event)
    {
        // Todo Send user an email
        $job = new ToCustomer($event->user, $event->password);
        $job->delay(Carbon::now()->addSecond());
        dispatch($job);

        // Todo 如果是批发商客户, 还要通知网站的管理员
        if($event->user->role == UserGroup::$WHOLESALE_CUSTOMER){
//            Log::info('info',['msg'=>'Wholesaler create to admin: event listener']);
            $toAdmin = new ToAdmin($event->user);
            $toAdmin->delay(Carbon::now()->addSeconds(2));
            dispatch($toAdmin);
        }
        return false;
    }
}
