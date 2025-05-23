<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Message;
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
        $game = Game::query()->inRandomOrder()->first();
        return [
            'body' => fake()->realText,
            'user_id' => $game->users->random()->get('id'),
            'game_id' => $game->id,
        ];
    }
}
