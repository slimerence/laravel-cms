<?php

namespace App\Mail\Order\Approved;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order\Order;
use App\User;

class ToFinanceController extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $financeController;
    public $viewOrderUrl;

    /**
     * ToFinanceController constructor.
     * @param Order $order
     * @param User $financeController
     */
    public function __construct(Order $order, User $financeController)
    {
        $this->order = $order;
        $this->financeController = $financeController;
        // 设置让 Finance Controller 来Approve或者reject的链接放到电子邮件中
        $this->viewOrderUrl = url('frontend/orders/approve/'.$financeController->uuid.'/'.$order->uuid);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(
            $this->financeController->name
            .', You have approved Order #'.$this->order->serial_number.', thanks!'
        )->markdown(
            _get_frontend_theme_prefix().'.email.order.approved.to_finance_controller'
        );
    }
}
