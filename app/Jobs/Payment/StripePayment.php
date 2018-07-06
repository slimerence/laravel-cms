<?php

namespace App\Jobs\Payment;

use App\Models\Order\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// 导入 Stripe 相关
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\Charge as StripeCharge;

class StripePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $request;
    public $user;
    private $apiKey;
    private $stripeCustomer = null;

    /**
     * StripePayment constructor.
     * 初始化 Stripe 收费的任务
     * @param Order $order
     * @param Request $request
     * @param User $user
     */
    public function __construct(Order $order, Request $request, User $user)
    {
        $this->order = $order;
        $this->request = $request;
        $this->user = $user;
        $this->apiKey = env('STRIPE_SECRET',false);
    }

    /**
     * 执行收费的操作
     * @return bool
     */
    public function handle()
    {
        if(!$this->apiKey){
            return false;
        }
        // todo 设置 API key
        Stripe::setApiKey($this->apiKey);

    }

    private function _createStripeCustomerAndGetId(){
        if($this->user){
            // 检查是否该客户已经有了 stripe ID
            $this->stripeCustomer = $this->user->getStripeCustomer();
            if(!$this->stripeCustomer){
                $this->stripeCustomer = $this->user->createStripeCustomer('');
            }
        }
    }
}
