<div>
  <div class="flex flex-wrap md:flex-nowrap">
    <div class="p-5 sm:mr-0 md:mr-5 sm:w-full md:w-2/6 dark:bg-gray-800 shadow-xl sm:rounded-lg">
      <div class="p-2 flex justify-between items-center">
        <h2 class="text-white text-3xl font-bold">Chats</h2>
        <button class="text-white text-3xl font-bold hover:cursor-pointer hover:text-blue-300">+</button>
      </div>

      <div class="h-96">
        @foreach($chats as $c)
          <a href="{{ route('chat', $c) }}"
             class="p-2 flex items-center my-3 hover:bg-gray-700 hover:cursor-pointer rounded-lg">
            <div>
              <div class="bg-white rounded-full relative" style="width: 50px; height: 50px;">
                    <span
                      class="rounded-full absolute bottom-0.5 right-0.5 bg-green-300 z-50"
                      style="height: 10px; width: 10px;"
                    ></span>
              </div>
            </div>
            <div class="px-5">
              <h3 class="text-white text-1xl font-bold">
                {{ $c->users->count() > 2 ? $c->title : $c->users->where('id', '!=', auth()->id())->first()->name }}
              </h3>
              <span class="text-gray-400">{{ str($c->messages->last()->message)->limit(25, ' ...') }}</span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
    <div class="sm:w-full md:w-4/6 dark:bg-gray-800 shadow-xl sm:rounded-lg">
      @if($chat)
        <div class="chat-area">
          <div class="chat-header p-3 flex justify-between items-center dark:bg-gray-800 border-b-2 border-gray-500">
            <h3 class="text-white text-2xl">
              {{ $chat->users->count() > 2 ? $chat->title : $chat->users->where('id', '!=', auth()->id())->first()->name }}
            </h3>
            <button class="text-white text-3xl font-bold hover:cursor-pointer hover:text-blue-300">+</button>
          </div>
          <div id="chat" class="px-5 md:h-[500px] overflow-auto">
            @foreach($chat->messages as $m)
              @if($m->user_id == auth()->id())
                <div class="my-message flex justify-end">
                  <div class="p-4 my-2 text-lg text-white bg-indigo-700 rounded-lg">
                    {{ $m->message }}
                  </div>
                </div>
              @else
                <div class="other-message flex justify-start">
                  <div class="p-4 my-2 text-lg text-white bg-gray-600 rounded-lg">
                    {{ $m->message }}
                  </div>
                </div>
              @endif
            @endforeach
          </div>

          <div class="mt-5 px-5">
              <div class="my-2">
                @foreach($typers as $userId => $data)
                  @if(auth()->id() != $userId)
                    <span class="px-2 text-gray-400">{{ $data['userName'] }} is typing</span>
                  @endif
                @endforeach
              </div>
            <form wire:submit.prevent="send" class="flex justify-between items-center">
              <lable class="w-full">
                <input wire:model.live.debounce.300ms="message" type="text"
                       class="w-full p-3 rounded-3xl border-0 focus:outline-none bg-gray-600 text-gray-200 placeholder-gray-400"
                       placeholder="Type a message...">
              </lable>
              <div class="p-2">
                <button type="submit" class="p-3 bg-indigo-700 rounded-full text-white hover:bg-indigo-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                       viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>
      @else
        <div class="h-full flex justify-center items-center">
          <h4 class="text-3xl font-bold text-gray-400">
            Nothing there yet.
          </h4>
        </div>
      @endif
    </div>
  </div>
</div>

@if($chat)
  @script
  <script>
    let elem = document.getElementById('chat');
    elem.scrollTop = elem.scrollHeight;

    $wire.on('new-message-sent', () => {
      let elem = document.getElementById('chat');
      elem.scrollTop = elem.scrollHeight;
    });
  </script>
  @endscript
@endif
