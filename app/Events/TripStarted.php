<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Child;
use Illuminate\Broadcasting\PrivateChannel;
class TripStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Child $child
     */
    public function __construct(private Child $child)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('started_trip_father.' . $this->child->fathers()->first()->id);
    }

    public function broadcastWith()
    {
        return [
            'message' => 'تم بدء رحلة الطفل ' . $this->child->name,
            'child_id' => $this->child->id,
            'child_name' => $this->child->name,
            'father_id' => $this->child->fathers()->first()->id,
        ];
    }
}
