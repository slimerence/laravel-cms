<?php
/**
 * 订单创建后给财务发的邮件
 */
namespace App\Mail\Order\Created;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order\Order;
use App\User;

class ToFinanceController extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $viewOrderUrl;

    /**
     * OrderPendingToFinanceController constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
        // 设置让 Finance Controller 来Approve或者reject的链接放到电子邮件中
        $this->viewOrderUrl = url('frontend/approve_order/'.$user->uuid.'/'.$order->uuid);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(
            $this->user->name.', New Order #'.$this->order->serial_number.' is pending and waiting for you to approve'
        )->markdown(
            _get_frontend_theme_prefix().'.email.order.created.pending_to_finance_controller'
        );
    }
}
