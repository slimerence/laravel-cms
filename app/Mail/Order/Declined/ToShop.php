<?php

namespace App\Mail\Order\Declined;

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
    public $reason;
    public $viewOrderUrl;

    /**
     * OrderPendingToPurchaser constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user, $reason)
    {
        $this->order = $order;
        $this->financeController = $user;
        $this->reason = $reason;
        $this->viewOrderUrl = url('backend/orders/view/'.$order->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(
            ', Order #'.$this->order->serial_number.' has been declined by '.$this->financeController->name)
            ->markdown(
                _get_frontend_theme_prefix().'.email.order.declined.to_shop'
            );
    }
}
