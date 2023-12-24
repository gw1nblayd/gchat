<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

         User::factory()->create([
             'email' => 'test11@test11.test11',
         ]);

        User::factory()->create([
            'email' => 'test12@test12.test12',
        ]);
    }
}
