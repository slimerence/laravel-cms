<?php

namespace App\Events\Order;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Order\Order;
use App\User;

class Declined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $user;   // 批准人
    public $reason;   // 原因

    /**
     * OrderApproved constructor. 订单 + 批准人
     * @param Order $order
     * @param User $user
     * @param string $reason
     */
    public function __construct(Order $order, User $user, $reason = 'N/A')
    {
        $this->order = $order;
        $this->user = $user;
        $this->reason = $reason;
    }
}
