<?php

namespace App\Listeners\Order;

use App\Events\Order\Approved;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Order\Approved\ToCustomer;
use App\Jobs\Order\Approved\ToShop;
use App\Jobs\Order\Approved\ToFinanceController;
use Carbon\Carbon;

class ApprovedEventListener implements ShouldQueue
{
    /**
     * OrderApprovedEventListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Approved  $event
     * @return boolean
     */
    public function handle(Approved $event)
    {
        // Todo: 给客户发邮件
        $jobOfSendEmailToCustomer = new ToCustomer($event->order, $event->user);
        $jobOfSendEmailToCustomer
            ->delay(Carbon::now()->addSecond());
        dispatch($jobOfSendEmailToCustomer);

        // Todo: 给商户发邮件
        $jobOfSendEmailToShop = new ToShop($event->order, $event->user);
        $jobOfSendEmailToShop
            ->delay(Carbon::now()->addSeconds(2));
        dispatch($jobOfSendEmailToShop);

        // Todo: 给Finance Controller发邮件
        if($event->user){
            // 如果给了 Finance Controller
            $jobOfSendEmailToController = new ToFinanceController($event->order, $event->user);
            $jobOfSendEmailToController
                ->delay(Carbon::now()->addSeconds(3));
            dispatch($jobOfSendEmailToController);
        }
        return false;
    }
}
