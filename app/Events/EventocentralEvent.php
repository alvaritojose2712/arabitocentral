<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventocentralEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private string $canal;
    private string $eventotipo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $eventotipo, string $canal)
    {
        $this->eventotipo = $eventotipo;
        $this->canal = $canal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('private.eventocentral.'.$this->canal);
        //return new PrivateChannel('channel-name');
    }
    public function broadcastAs()
    {
        return "eventocentral";
    }
    public function broadcastWith()
    {
        return [
            "eventotipo" => $this->eventotipo,
        ];
    }
}
