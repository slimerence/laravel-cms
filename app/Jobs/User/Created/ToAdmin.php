<?php

namespace App\Jobs\User\Created;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Admin\WholesaleCreated;

class ToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * ToCustomer constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
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
            if($this->user){
                Mail::to(config('system.ADMIN_EMAIL'))
                    ->send(
                        new WholesaleCreated(
                            $this->user
                        )
                    );
            }else{
                $this->_error_handler();
            }
        }
    }

    /**
     * 测试方法
     */
    private function _test_mode_handler(){
        Log::info('Info',
            [
                'Type'=>'Send email to admin when a wholesaler account created',
                'User'=>$this->user ? $this->user : 'No User'
            ]
        );
    }

    /**
     *  检查出User为空时的处理
     */
    private function _error_handler(){
        Log::error('Wholesaler is NULL when it\'s account created!');
        return;
    }
}
