<?php

namespace App\Listeners\Order;

use App\Events\Order\Declined;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Jobs\Order\Declined\ToCustomer;
use App\Jobs\Order\Declined\ToShop;
use App\Jobs\Order\Declined\ToFinanceController;

class DeclinedEventListener implements ShouldQueue
{
    /**
     * DeclinedEventListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Declined  $event
     * @return boolean
     */
    public function handle(Declined $event)
    {

        // Todo: 给客户发邮件
        $jobOfSendEmailToCustomer = new ToCustomer($event->order, $event->user, $event->reason);
        $jobOfSendEmailToCustomer
            ->delay(Carbon::now()->addSecond());
        dispatch($jobOfSendEmailToCustomer);

        // Todo: 给商户发邮件
        $jobOfSendEmailToShop = new ToShop($event->order, $event->user, $event->reason);
        $jobOfSendEmailToShop
            ->delay(Carbon::now()->addSeconds(2));
        dispatch($jobOfSendEmailToShop);

        // Todo: 给Finance Controller发邮件
        if($event->user){
            // 如果给了 Finance Controller
            $jobOfSendEmailToController = new ToFinanceController($event->order, $event->user, $event->reason);
            $jobOfSendEmailToController
                ->delay(Carbon::now()->addSeconds(3));
            dispatch($jobOfSendEmailToController);
        }
        return false;
    }
}
