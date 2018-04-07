<?php

namespace App\Jobs\Order\Declined;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use App\User;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Log;
use App\Models\Utils\UserGroup;
use App\Mail\Order\Declined\ToFinanceController as MailToFinanceController;

class ToFinanceController implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $financeController;   // 批准人
    public $reason;   // 批准人

    /**
     * 订单创建后通知买方的财务主管
     * @param Order $order
     * @param User $financeController
     */
    public function __construct(Order $order, User $financeController, $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
        $this->financeController = (
            $financeController->role === UserGroup::$FINANCE_CONTROLLER ? $financeController : null
        );
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
                Mail::to($this->financeController->email)
                    ->send(new MailToFinanceController($this->order, $this->financeController, $this->reason));
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
                'Mode'=>'Test Decline Email To Finance Controller',
                'FinanceController'=>$this->financeController,
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
        Log::error('User Or Order is NULL when sending decline email to Finance Controller!');
        return;
    }
}
