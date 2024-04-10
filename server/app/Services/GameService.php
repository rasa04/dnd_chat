<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Game;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final readonly class GameService
{
    /**
     * @throws Exception
     */
    public static function create(array $data): Builder|Model
    {
        try {
            DB::beginTransaction();
            /** @var Game $game */
            $game = Game::query()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'password' => Hash::make($data['password'])
            ]);
            $game->users()->attach(Auth::id());
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return $game;
    }

    /**
     * @param array $data
     * @return bool true - if joined, false - if already joined
     */
    public static function join(array $data): bool
    {
        $user = Auth::user();
        /** @var Game $game */
        if (
            !empty($game = Game::query()->find($data['id']))
            && is_null($game->users->find($user))
        ) {
            $game->users()->attach($user);

            return true;
        }

        return false;
    }

    public static function get(int $limit = 50): Collection
    {
        return Game::query()->limit($limit)->get();
    }
}
