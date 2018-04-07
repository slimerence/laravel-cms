<?php

namespace App\Events\Order;

use App\Models\Order\Order;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class Approved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $user;   // 批准人

    /**
     * OrderApproved constructor. 订单 + 批准人
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
    }
}
