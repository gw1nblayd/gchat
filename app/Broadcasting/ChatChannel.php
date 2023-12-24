<?php

namespace App\Broadcasting;

use App\Models\Chat;
use App\Models\User;

class ChatChannel
{
    public function __construct()
    {
        //
    }

    public function join(User $user, string $chatUid): array|bool
    {
        return Chat::query()
            ->where('uid', $chatUid)
            ->whereHas(
                'users',
                fn($q) => $q->where('user_id', auth()->id()),
            )
            ->count();
    }
}
