<?php
/**
 * 当订单以 Place Order 的方式被提交时出发此事件
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Gloudemans\Shoppingcart\Cart;
use App\User;
use Illuminate\Http\Request;
use App\Models\Order\Order;

class OrderPlaced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cart;
    public $customer;
    public $request;
    public $order;

    /**
     * OrderPlaced constructor.
     * @param Cart $cart
     * @param User $customer
     * @param Request $request
     * @param Order $order
     */
    public function __construct(Cart $cart, User $customer, Request $request, Order $order)
    {
        $this->cart = $cart;
        $this->customer = $customer;
        $this->request = $request;
        $this->order = $order;
    }
}
