<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Events\Typing;
use App\Models\Chat as ChatModel;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    public $chats = [];
    public array $typers = [];

    public ?ChatModel $chat = null;

    #[Validate('required|string')]
    public string $message = '';

    public function mount(): void
    {
        $this->chats = ChatModel::query()->my()->with(['messages.user', 'users'])->get();

        $this->chat?->load(['messages.user', 'users']);
    }

    public function updatedMessage(): void
    {
        broadcast(new Typing($this->chat->uid, auth()->id(), $this->message));
    }

    public function getListeners(): array
    {
        if (! $this->chat) {
            return [];
        }

        return [
            "echo-private:chat.{$this->chat->uid},MessageSent" => 'updateChat',
            "echo-private:chat.{$this->chat->uid},Typing" => 'updateTyper',
        ];
    }

    public function updateChat(): void
    {
        $this->chat->refresh()->with(['messages.user']);
        $this->dispatch('new-message-sent');
    }

    public function updateTyper(array $data): void
    {
        if ($data['message']) {
            $this->typers[$data['userId']] = [
                'userName' => $this->chat->users->where('id', $data['userId'])?->first()->name,
                'message' => $data['message'],
            ];
        } else {
            unset($this->typers[$data['userId']]);
        }
    }

    public function send(): void
    {
        if (! $this->chat) {
            return;
        }

        $this->validate();

        $message = $this->chat?->messages()->create([
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);


        broadcast(new MessageSent($this->chat, $message));
        broadcast(new Typing($this->chat->uid, auth()->id(), ''));

        $this->dispatch('new-message-sent');

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
