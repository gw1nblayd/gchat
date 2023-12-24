<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Chat $chat = null)
    {
        if ($chat && !$chat->isMy()) {
            $chat = null;
        }

        return view('dashboard', compact('chat'));
    }
}
