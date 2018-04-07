<?php

namespace App\Jobs\Order\Created;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order\Order;
use App\User;
use App\Models\Utils\UserGroup;
use App\Models\Utils\EmailTool;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Order\Created\ToFinanceController as CreatedToFinanceController;

class ToFinanceController implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $user;
    private $type;

    /**
     * 订单创建后通知买方的财务主管
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = (
            $user->role === UserGroup::$FINANCE_CONTROLLER ? $user : null
        );
        $this->type = EmailTool::$TYPE_ORDER_PENDING_TO_FINANCE_CONTROLLER;
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
                Mail::to($this->user->email)
                    ->send(new CreatedToFinanceController($this->order, $this->user));
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
                'Mode'=>'Test Mode Email To Finance Controller',
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
        Log::error('User Or Order is NULL when sending email to Finance Controller!');
        return;
    }
}
