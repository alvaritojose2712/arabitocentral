<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevaTarea implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sucursalid;
    
    public function __construct($sucursalid)
    {
        $this->sucursalid = $sucursalid;
    }

   
    public function broadcastOn()
    {
       // return new Channel('central');
        return new Channel('centra');

    }

    public function broadcastWith()
    {
        return ['id' => $this->sucursalid];
    }
    // public function broadcastAs()
    // {
    //     return 'NuevaTarea';
    // }
}
