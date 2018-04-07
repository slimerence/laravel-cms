<?php
/**
 * 向买方发送的邮件
 */
namespace App\Mail\Order\Created;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order\Order;
use App\User;

class ToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $order;
    public $viewOrderUrl;

    /**
     * OrderPendingToPurchaser constructor.
     * @param Order $order
     * @param User $customer
     */
    public function __construct(Order $order, User $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->viewOrderUrl = url('frontend/view_order/'.$customer->uuid.'/'.$order->uuid);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customer->name.', Order #'.$this->order->serial_number.' is confirmed and waiting for approval')
            ->markdown(
                _get_frontend_theme_prefix().'.email.order.created.pending_to_purchaser'
            );
    }
}
