<?php

namespace App\Mail\Order\Approved;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order\Order;
use App\User;

class ToShop extends Mailable
{
    use Queueable, SerializesModels;

    public $financeController;
    public $order;
    public $viewOrderUrl;

    /**
     * ToShop constructor.
     * @param Order $order
     * @param User|null $financeController
     */
    public function __construct(Order $order, User $financeController=null)
    {
        $this->order = $order;
        $this->financeController = $financeController;
        $this->viewOrderUrl = url('backend/orders/view/'.$order->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->order){
            return $this->subject(
                'Order #'.$this->order->serial_number.' has been approved by '.$this->financeController->name)
                ->markdown(
                    _get_frontend_theme_prefix().'.email.order.approved.to_shop'
                );
        }
    }
}
