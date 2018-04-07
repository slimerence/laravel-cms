<?php

namespace App\Mail\Order\Declined;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order\Order;
use App\User;
use App\Models\Utils\UserGroup;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Mail\Order\Declined\ToFinanceController as MailToFinanceController;

class ToFinanceController extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $reason;
    public $financeController;

    /**
     * ToFinanceController constructor.
     * @param Order $order
     * @param User $financeController
     * @param string $reason
     */
    public function __construct(Order $order, User $financeController, $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
        $this->financeController = $financeController;
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
            .', You have declined Order #'.$this->order->serial_number.', thanks!'
        )->markdown(
            _get_frontend_theme_prefix().'.email.order.declined.to_finance_controller'
        );
    }
}
