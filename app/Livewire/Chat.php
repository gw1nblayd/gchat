<?php

namespace App\Livewire;

use App\Models\Chat as ChatModel;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    public ?ChatModel $chat = null;

    #[Validate('required|string')]
    public string $message = '';

    public function send()
    {
        //
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
