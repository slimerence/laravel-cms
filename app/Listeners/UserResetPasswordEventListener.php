<?php

namespace App\Listeners;

use App\Events\UserResetPassword;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserResetPasswordEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserResetPassword  $event
     * @return void
     */
    public function handle(UserResetPassword $event)
    {
        //
    }
}
