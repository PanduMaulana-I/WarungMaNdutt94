<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $status;

    public function __construct($orderId, $status)
    {
        $this->orderId = $orderId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        // Channel publik (karena buyer nggak login pake token private)
        return new Channel('orders.' . $this->orderId);
    }

    public function broadcastAs()
    {
        // Ini HARUS cocok dengan .listen('.OrderStatusUpdated') di JS
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
    {
        // Data yang dikirim ke browser
        return [
            'order_id' => $this->orderId,
            'status' => $this->status,
        ];
    }
}
