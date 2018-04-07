<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $password;

    /**
     * UserCreated constructor.
     * @param User $user
     * @param string $initPassword
     */
    public function __construct(User $user, $initPassword='123456')
    {
        $this->user = $user;
        $this->password = $initPassword;
    }
}
