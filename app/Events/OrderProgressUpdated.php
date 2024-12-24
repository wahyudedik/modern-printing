<?php

namespace App\Events;

use App\Models\Transaksi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderProgressUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaksi;

    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('orders.' . $this->transaksi->id);
    }

    public function broadcastWith()
    {
        return [
            'status' => $this->transaksi->status,
            'progress' => $this->transaksi->progress_percentage,
            'stage' => $this->transaksi->current_stage
        ];
    }
}
