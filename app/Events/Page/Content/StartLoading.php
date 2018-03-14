<?php

namespace App\Events\Page\Content;

use App\Models\Contract\ISupportWidget;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartLoading
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $page;
    public $dataForView;

    /**
     * StartLoading constructor.
     * @param ISupportWidget $page
     * @param array $data   // 按引用传递来的
     */
    public function __construct(ISupportWidget $page, array &$data)
    {
        $this->page = $page;
        $data['widgets'] = $page->locateWidgets();
        $this->dataForView = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
