<?php

namespace App\Listeners\Order;

use App\Events\Order\Created as OrderCreated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Order\Created\ToCustomer;
use App\Jobs\Order\Created\ToShop;
use App\User;

class CreatedEventListener
{
    /**
     * CreatedEventListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * 监听订单创建的事件. ( 禁止冒泡 )
     * 1: 给客户发邮件
     * 2: 给商户发邮件
     * 3: 给Finance Controller发邮件(可选)
     * @param  OrderCreated  $event
     * @return boolean
     */
    public function handle(OrderCreated $event)
    {
        // Todo: 给客户发邮件
        $jobOfSendEmailToCustomer = new ToCustomer($event->order, $event->customer);
        $jobOfSendEmailToCustomer
            ->delay(Carbon::now()->addSecond());
        dispatch($jobOfSendEmailToCustomer);

        // Todo: 给商户发邮件
        $admin = User::find(1);  // 取得管理员
        $jobOfSendEmailToShop = new ToShop($event->order, $admin);
        $jobOfSendEmailToShop
            ->delay(Carbon::now()->addSeconds(2));
        dispatch($jobOfSendEmailToShop);

        // Todo: 给Finance Controller发邮件
//        $financeController = $event->customer->getFinanceController($event->order->group_id);
//        if($financeController){
//            $jobOfSendEmailToController = new ToFinanceController($event->order, $financeController);
//            $jobOfSendEmailToController
//                ->delay(Carbon::now()->addSeconds(3));
//            dispatch($jobOfSendEmailToController);
//        }

        return false;
    }
}
