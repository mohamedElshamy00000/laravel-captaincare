<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Child;
use App\Models\Father;

class ChildGotInCarEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Father $father
     * @param Child $child
     * @return void
     */
    public function __construct(private Father $father, private Child $child)
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('got_in_car_father.' . $this->father->id);
    }

    public function broadcastAs()
    {
        return 'child-got-in-car';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'child_id' => $this->child->id,
            'child_name' => $this->child->name,
            'father_id' => $this->father->id,
            'message' => 'الطفل ' . $this->child->name . ' قد وصل السيارة',
        ];
    }
}
