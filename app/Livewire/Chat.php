<?php

namespace App\Livewire;

use App\Enums\ChatType;
use App\Events\MessageSent;
use App\Events\Typing;
use App\Models\Chat as ChatModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    public $chats = [];

    public bool $isCreate = false;
    public string $userSearch = '';
    public string $chatTitle = '';
    public array $selectedUsers = [];
    public $searchedUsers = [];
    public array $typers = [];

    public ?ChatModel $chat = null;

    #[Validate('required|string')]
    public string $message = '';

    public function mount(): void
    {
        $this->chats = ChatModel::query()
            ->my()->with(['messages.user', 'users'])
            ->orderByDesc('updated_at')
            ->get();

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

    public function toggleCreate(): void
    {
        $this->isCreate = ! $this->isCreate;
    }

    public function addUser(User $user): void
    {
        $this->selectedUsers[] = $user;
    }

    public function removeUser(int $index): void
    {
        unset($this->selectedUsers[$index]);
    }

    public function createChat(string $type)
    {
        $validationRules = [
            'chatTitle' => 'nullable|string',
            'selectedUsers' => 'required|array|min:1',
        ];

        if (count($this->selectedUsers) > 1) {
            $validationRules['chatTitle'] = 'required|string|min:3';
        }

        $this->validate($validationRules);

        $type = ChatType::tryFrom($type);

        $chat = ChatModel::query()->create([
            'uid' => Str::uuid(),
            'title' => $this->chatTitle,
            'type' => $type,
        ]);

        $chat
            ->users()
            ->attach([
                ...collect($this->selectedUsers)->pluck('id')->toArray(),
                auth()->id(),
            ]);

        $this->chats->push($chat);

        $this->isCreate = false;
        $this->chatTitle = '';
        $this->selectedUsers = [];

        return to_route('chat', $chat);
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

        $this->chat->update(['updated_at' => now()]);

        broadcast(new MessageSent($this->chat, $message));
        broadcast(new Typing($this->chat->uid, auth()->id(), ''));

        $this->dispatch('new-message-sent');

        $this->message = '';
    }

    public function render()
    {
        if ($this->userSearch) {
            $ids = collect($this->selectedUsers)->pluck('id');
            $userIdsFromChats = ChatModel::query()
                ->whereIn('id', $this->chats->pluck('id'))
                ->has('users', '=', 2)
                ->with('users')
                ->get()
                ->pluck('users')
                ->flatten()
                ->where('id', '!=', auth()->id())
                ->pluck('id')
                ->toArray();

            $this->searchedUsers = Collection::make(
                User::query()
                    ->where(
                        fn($q) => $q
                            ->where('id', $this->userSearch)
                            ->orWhere('email', 'like', "%{$this->userSearch}%")
                            ->orWhere('email', 'like', "%{$this->userSearch}%"),
                    )
                    ->whereNotIn('id', [...$userIdsFromChats, ...$ids, auth()->id()])
                    ->limit(3)
                    ->get(),
            );
        }

        return view('livewire.chat');
    }
}
