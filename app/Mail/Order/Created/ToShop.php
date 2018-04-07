<?php
/**
 * 订单创建后给店主发的邮件
 */
namespace App\Mail\Order\Created;
use App\Models\Order\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ToShop extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $viewOrderUrl;
    public $financeController;

    /**
     * OrderPendingToShop constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
        $this->viewOrderUrl = url('backend/orders/view/'.$order->id);
//        $this->financeController = $order->customer->getFinanceController($order->group_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->user->name.', New Order #'.$this->order->serial_number.' is confirmed and waiting for approval')
            ->markdown(
                _get_frontend_theme_prefix().'.email.order.created.pending_to_shop'
            );
    }
}
