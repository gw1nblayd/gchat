<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Typing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $chatUid,
        public string $userId,
        public string $message,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->chatUid}"),
        ];
    }
}
