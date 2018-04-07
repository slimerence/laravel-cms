<?php

namespace App\Jobs\Order\Approved;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order\Order;
use App\User;
use Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Order\Approved\ToShop as MailToShop;

class ToShop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $financeController;   // 批准人

    /**
     * ToShop constructor.
     * @param Order $order
     * @param User|null $financeController
     */
    public function __construct(Order $order, User $financeController = null)
    {
        $this->order = $order;
        $this->financeController = $financeController;
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
                Mail::to(config('system.ADMIN_EMAIL'))
                    ->send(new MailToShop($this->order, $this->financeController));
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
                'Mode'=>'Test Mode Email To Shop',
                'Customer'=>$this->financeController,
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
        Log::error('User Or Order is NULL when sending approval email to Shop!');
        return;
    }
}
