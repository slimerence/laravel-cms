<?php

namespace App\Events\Order;

use App\Models\Order\Order;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;

/**
 * 创建新订单的事件, 需要传入订单和客户两个对象
 * @package App\Events
 */
class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $customer;
    public $request;

    /**
     * Created constructor.
     * @param Order $order
     * @param User $customer
     * @param Request $request
     */
    public function __construct(Order $order, User $customer, Request $request)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->request = $request;
    }
}
