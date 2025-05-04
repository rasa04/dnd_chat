<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $game = User::query()->inRandomOrder()->first();

        return [
            'name' => $this->faker->slug,
            'description' => $this->faker->realText,
            'game_master_id' => $game->users->random()->get('id'),
            'password' => '12345678',
        ];
    }
}
