<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TripStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $child;

    public function __construct($child)
    {
        $this->child = $child;
    }

    public function broadcastOn()
    {
        return new Channel('trips');
    }

    public function broadcastWith()
    {
        return [
            'message' => 'The trip for your child ' . $this->child->name . ' has started.',
            'child_id' => $this->child->id,
        ];
    }
}
