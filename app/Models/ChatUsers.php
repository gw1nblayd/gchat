<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatUsers extends Pivot
{
    protected $table = 'chat_user';
}
