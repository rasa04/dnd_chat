<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $game = Game::inRandomOrder()->first();
        return [
            'body' => fake()->realText,
            'user_id' => $game->users->random()->id,
            'game_id' => $game->id
        ];
    }
}
