<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\Payment\WeChat;
use App\Models\Utils\PaymentTool;
use App\Jobs\Payment\Paypal as PayPalJob;

class OrderPlacedEventListener
{
    /**
     * OrderPlacedEventListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $job = null;
        $redirect = null;
        // Todo 不同的支付方式, 处理支付交易
        switch ($event->order->payment_type){
            case PaymentTool::$TYPE_PAYPAL:
                $job = new PayPalJob($event->order, 'PayPal_Express');
                break;
            default:
                break;
        }
        if($job){
            $job->handle();
        }
    }
}
