<?php

namespace App\Jobs\User\Created;

use App\Models\UserGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Mail\UserConfirmEmail;
use App\User;
use Mail;

class ToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * ToCustomer constructor.
     * @param User $user
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
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
                Mail::to($this->user->email)
                    ->send(
                        new UserConfirmEmail(
                            $this->user,
                            $this->password
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
                'Type'=>'Send email to user when it\'s created',
                'User'=>$this->user ? $this->user : 'No User',
                'Password'=>$this->password,
            ]
        );
    }

    /**
     *  检查出User为空时的处理
     */
    private function _error_handler(){
        Log::error('User is NULL when it\'s account created!');
        return;
    }
}
