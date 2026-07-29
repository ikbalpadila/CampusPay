<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $userId,
        public string $type,
        public string $title,
        public string $message,
        public array  $data = []
    ) {}

    // Channel per user — setiap mahasiswa dengarkan channel miliknya
    public function broadcastOn(): array
    {
        return [
            new Channel("notifications.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'type'    => $this->type,
            'title'   => $this->title,
            'message' => $this->message,
            'data'    => $this->data,
            'time'    => now()->toISOString(),
        ];
    }
}