<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Game;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (
            User::query()->count('id') > 0
            && Game::query()->count('id') > 0
            && Message::query()->count('id') > 0
        ) {
            return;
        }

//        $users = User::factory()
//            ->count(100)
//            ->create();
//
//        Game::factory()
//            ->count(10)
//            ->hasAttached($users->random(10))
//            ->create();
//
//        Message::factory()
//            ->count(1000)
//            ->create();

    }
}
