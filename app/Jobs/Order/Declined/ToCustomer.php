<?php

namespace App\Jobs\Order\Declined;

use App\Models\Order\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Order\Declined\ToCustomer as MailToCustomer;

class ToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $financeController;
    public $reason;

    /**
     * ToCustomer constructor.
     * @param Order $order
     * @param User $financeController
     * @param string $reason
     */
    public function __construct(Order $order, User $financeController, $reason)
    {
        $this->order = $order;
        $this->financeController = $financeController;
        $this->reason = $reason;
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
            if($this->financeController && $this->order){
                Mail::to($this->order->customer->email)
                    ->send(new MailToCustomer($this->order, $this->financeController, $this->reason));
            }else{
                $this->_error_handler();
            }
        }
    }

    /**
     * 测试环境下执行的方法
     */
    private function _test_mode_handler(){
        if($this->financeController && $this->order){
            Log::info('Info',[
                'Mode'=>'Test Mode Order Approved',
                'Customer'=>$this->financeController,
                'Order'=>$this->order,
                'Reason'=>$this->reason
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
        Log::error('Customer Or Order is NULL when sending email for Approval!');
        return;
    }
}
