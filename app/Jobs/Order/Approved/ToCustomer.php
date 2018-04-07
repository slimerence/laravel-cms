<?php

namespace App\Jobs\Order\Approved;

use App\Models\Order\Order;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Order\Approved\ToCustomer as OrderApprovedToCustomer;
use Illuminate\Support\Facades\Log;

class ToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $user;   // 批准人

    /**
     * ToCustomer constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
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
            if($this->user && $this->order){
                Mail::to($this->order->customer->email)
                    ->send(new OrderApprovedToCustomer($this->order, $this->user));
            }else{
                $this->_error_handler();
            }
        }
    }

    /**
     * 测试环境下执行的方法
     */
    private function _test_mode_handler(){
        if($this->user && $this->order){
            Log::info('Info',[
                'Mode'=>'Test Mode Order Approved',
                'Customer'=>$this->user,
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
        Log::error('Customer Or Order is NULL when sending email for Approval!');
        return;
    }
}
