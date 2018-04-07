<?php

namespace App\Mail\Admin;

use App\Models\Wholesaler;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;
//use Illuminate\Support\Facades\Log;

class WholesaleCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $wholesaler;
    public $loginUrl;

    /**
     * WholesaleCreated constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->wholesaler = Wholesaler::where('user_id',$user->id)->orderBy('id','desc')->first();
        $this->loginUrl = url('login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        Log::info('info',['msg'=>'Wholesaler create to admin']);
        if($this->wholesaler){
            return $this->subject('A new wholesaler account has been created: '.$this->wholesaler->registered_name_of_applicant)
                ->markdown(_get_frontend_theme_prefix().'.email.wholesaler.to_admin');
        }
    }
}
