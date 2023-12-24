<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'test11@test11.test11',
        ]);

        User::factory()->create([
            'email' => 'test12@test12.test12',
        ]);

        User::factory(10)->create();

        Chat::create([
            'uid' => str()->uuid(),
            'title' => 'Test chat',
        ])->users()->attach([1, 2]);

        ChatMessage::create([
            'chat_id' => 1,
            'user_id' => 1,
            'message' => 'Hello!',
        ]);

        ChatMessage::create([
            'chat_id' => 1,
            'user_id' => 1,
            'message' => 'How are you?',
        ]);

        ChatMessage::create([
            'chat_id' => 1,
            'user_id' => 2,
            'message' => 'Thanks, I\'m fine!',
        ]);

        ChatMessage::create([
            'chat_id' => 1,
            'user_id' => 2,
            'message' => 'What about you?',
        ]);
    }
}
