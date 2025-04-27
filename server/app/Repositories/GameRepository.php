<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Game;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class GameRepository extends AbstractRepository
{
    public function findOneById(int $id): ?Game
    {
        /** @var ?Game */
        return Game::query()->firstWhere('id', '=', $id);
    }

    public function exists(int $id): bool
    {
        return Game::query()->where('id', '=', $id)->exists();
    }

    public function findMany($limit = self::MAX_LIMIT): Collection
    {
        return Game::query()->limit($limit)->get();
    }

    /**
     * @throws Exception
     */
    public function createOneAndAttachUserById(array $data, int $userId): Game
    {
        try {
            DB::beginTransaction();
            $game = Game::query()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'password' => Hash::make($data['password'])
            ]);
            $game->users()->attach($userId);
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        /** @var Game */
        return $game;
    }
}
