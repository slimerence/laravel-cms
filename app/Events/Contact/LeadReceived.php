<?php

namespace App\Events\Contact;

use App\Models\Lead;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeadReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lead;
    public $adminEmail;

    /**
     * @param Lead $lead
     * @param String $adminEmail
     */
    public function __construct(Lead $lead, $adminEmail)
    {
        $this->lead = $lead;
        $this->adminEmail = $adminEmail;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return false;
//        return new PrivateChannel('channel-name');
    }
}
