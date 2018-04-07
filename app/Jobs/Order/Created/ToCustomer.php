<?php

namespace App\Jobs\Order\Created;

use App\Models\Utils\EmailTool;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order\Order;
use App\User;
use Mail;
use App\Mail\Order\Created\ToCustomer as OrderCreatedToCustomer;
use Illuminate\Support\Facades\Log;

class ToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $customer;
    private $type;

    /**
     * 订单创建后通知买方
     * @param Order $order
     * @param User $customer
     */
    public function __construct(Order $order, User $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->type = EmailTool::$TYPE_ORDER_PENDING_TO_PURCHASER;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(config('system.RUNNING_IN_TEST_MODE')){
            $this->_test_mode_handler();
        }else{
            // 运行环境
            if($this->customer && $this->order){
                Mail::to($this->customer->email)
                    ->send(new OrderCreatedToCustomer($this->order, $this->customer));
            }else{
                $this->_error_handler();
            }
        }
    }

    /**
     * 测试环境下执行的方法
     */
    private function _test_mode_handler(){
        if($this->customer && $this->order){
            Log::info('Info',[
                'Mode'=>'Test Mode',
                'Customer'=>$this->customer,
                'Order'=>$this->order
            ]);
        }else{
            $this->_error_handler();
        }
        return;
    }

    /**
     *  检查出order或者customer为空时的处理
     */
    private function _error_handler(){
        Log::error('Customer Or Order is NULL when sending email for confirmation!');
        return;
    }
}
