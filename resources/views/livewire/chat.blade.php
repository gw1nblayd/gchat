<div>
  <div class="flex flex-wrap md:flex-nowrap">
    <div class="p-5 sm:mr-0 md:mr-5 sm:w-full md:w-2/6 dark:bg-gray-800 shadow-xl sm:rounded-lg">
      <div class="p-2 flex justify-between items-center">
        <h2 class="text-white text-3xl font-bold">Chats</h2>
        <button class="text-white text-3xl font-bold hover:cursor-pointer hover:text-blue-300">+</button>
      </div>

      <div>
        <a wire:navigate href="{{ route('chat') }}"
           class="p-2 flex items-center my-3 hover:bg-gray-700 hover:cursor-pointer rounded-lg">
          <div>
            <div class="bg-white rounded-full relative" style="width: 50px; height: 50px;">
                    <span class="rounded-full absolute bottom-0.5 right-0.5 bg-green-300 z-50"
                          style="height: 10px; width: 10px;"></span>
            </div>
          </div>
          <div class="px-5">
            <h3 class="text-white text-1xl font-bold">Archee</h3>
            <span class="text-gray-400">Как твои ..</span>
          </div>
        </a>
      </div>
    </div>
    <div class="p-5 sm:w-full md:w-4/6 dark:bg-gray-800 shadow-xl sm:rounded-lg">
      @if($chat)
        <div class="chat-area">
          <div class="chat">
            <div class="my-message flex justify-end">
              <div class="p-4 my-2 text-lg text-white bg-indigo-700 rounded-lg">
                Hi
              </div>
            </div>
            <div class="other-message flex justify-start">
              <div class="p-4 my-2 text-lg text-white bg-gray-600 rounded-lg">
                Hey
              </div>
            </div>
            <div class="other-message flex justify-start">
              <div class="p-4 my-2 text-lg text-white bg-gray-600 rounded-lg">
                How are you?
              </div>
            </div>
            <div class="other-message flex justify-start">
              <div class="p-4 my-2 text-lg text-white bg-gray-600 rounded-lg">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet beatae, consectetur cumque illum ipsa
                ipsam
                laudantium mollitia quo sunt vel. A, alias beatae excepturi facilis harum nisi nostrum numquam ut?
              </div>
            </div>
            <div class="my-message flex justify-end">
              <div class="p-4 my-2 text-lg text-white bg-indigo-700 rounded-lg">
                I'm fine, thanks!
              </div>
            </div>

            <div class="my-message flex justify-end">
              <div class="p-4 my-2 text-lg text-white bg-indigo-700 rounded-lg">
                What about you?
              </div>
            </div>
          </div>

          <div class="mt-5">
            <div class="flex justify-between items-center">
              <lable class="w-full">
                <input wire:model="message" type="text"
                       class="w-full p-3 rounded-3xl border-0 focus:outline-none bg-gray-600 text-gray-200 placeholder-gray-400"
                       placeholder="Type a message...">
              </lable>
              <div class="p-2">
                <button wire:click="send" class="p-3 bg-indigo-700 rounded-full text-white hover:bg-indigo-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                       viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </button>
              </div>
            </div>
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
