<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Utils\UserGroup;

class UserConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $url;

    /**
     * UserConfirmEmail constructor.
     * @param User $user
     * @param string $password
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->url = url('login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->role == UserGroup::$GENERAL_CUSTOMER){
            return $this->subject($this->user->name.', Your '.config('app.name').' account has been created!')
                ->markdown(
                    _get_frontend_theme_prefix().'.email.user.confirm_email'
                );
        }

        if($this->user->role == UserGroup::$WHOLESALE_CUSTOMER){
            return $this->subject($this->user->name.', Your '.config('app.name').' account has been created!')
                ->markdown(
                    _get_frontend_theme_prefix().'.email.wholesaler.confirm_email'
                );
        }
    }
}
