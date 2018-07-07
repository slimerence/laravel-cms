<?php

namespace App\Jobs\Payment;

use App\Models\Configuration;
use App\Models\Order\Order;
use App\Models\Settings\PaymentMethod;
use App\Models\Utils\OrderStatus;
use App\Models\Utils\PaymentTool;
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
use Log;
use DB;

class StripePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var User
     */
    public $user;

    /**
     * @var PaymentMethod
     */
    public $paymentMethod;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var StripeCustomer
     */
    private $stripeCustomer = null;

    /**
     * 初始化 Stripe 收费的任务
     * @param Order $order
     * @param Request $request
     * @param User $user
     * @param PaymentMethod $paymentMethod
     */
    public function __construct(Order $order, Request $request, User $user, PaymentMethod $paymentMethod)
    {
        $this->order = $order;
        $this->request = $request;
        $this->user = $user;
        $this->paymentMethod = $paymentMethod;
        $this->apiKey = $this->paymentMethod->getApiSecret();
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

        // 获取Stripe customer
        $this->_createStripeCustomerAndGetId();
        if($this->stripeCustomer){
            $charge = $this->_charge();
            if($charge){
                $chargeResult = $this->_finalizeOrderAndCustomer();
                if($chargeResult){
                    // 成功了
                    $chargeResult = true;
                }else{
                    // 保存订单失败了
                    $chargeResult = false;
                    Log::info('DB_ERROR',['msg'=>'收款后保存订单失败']);
                }
            }else{
                $chargeResult = false;
                Log::info('DB_ERROR',['msg'=>'从Stripe收款失败!']);
            }
        }else{
            // 没有取得用户, 肯定失败了
            $chargeResult = false;
            Log::info('DB_ERROR',['msg'=>'创建Stripe客户失败!']);
        }
        return $chargeResult;
    }

    /**
     * 针对数据库进行更新的操作
     * @return bool
     */
    private function _finalizeOrderAndCustomer(){
        DB::beginTransaction();
        // 更新订单状态为已经支付
        $this->order->status = OrderStatus::$APPROVED;
        $saved = $this->order->save();

        // 如果需要, 保存客户的 stripe id
        if(is_null($this->user->stripe_id)){
            $this->user->stripe_id = $this->stripeCustomer->id;
            $saved = $this->user->save();
        }
        if($saved){
            DB::commit();
        }else{
            DB::rollback();
        }
        return $saved;
    }

    /**
     * 收取费用
     * @return \Stripe\ApiResource
     */
    private function _charge(){
        return StripeCharge::create([
            'customer'=>$this->stripeCustomer->id,
            'amount' => $this->order->getTotalFinal()*100, // In Cents
            'currency' => 'AUD',
            "description" => 'Payment for order serial # '.$this->order->serial_number,
            //
            "statement_descriptor" => env('APP_NAME','Testing'),
            // The email address to which this charge's receipt will be sent
            'receipt_email'=>Configuration::GetFinanceEmail()
        ]);
    }

    /**
     * 创建Customer, 如果用户的 stripe id 已经存在, 那么就去获取
     */
    private function _createStripeCustomerAndGetId(){
        if($this->user){
            // 检查是否该客户已经有了 stripe ID
            $this->stripeCustomer = $this->user->getStripeCustomer();
            if(!$this->stripeCustomer){
                $this->stripeCustomer = $this->user
                    ->createStripeCustomer(
                        $this->request->get(PaymentTool::STRIPE_TOKEN_INPUT_NAME)
                    );
            }
        }
    }
}
