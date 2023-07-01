<?php

namespace App\Services;

use App\Models\Game;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GameService
{
    /**
     * @throws Exception
     */
    public static function create(array $data): Game|Application|Response
    {
        try {
            DB::beginTransaction();
            $game = Game::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'password' => Hash::make($data['password'])
            ]);
            $game->users()->attach(Auth::id());
            DB::commit();
            return $game;
        } catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public static function join(array $data): void
    {
        $user = Auth::user();
        $game = Game::find($data['id']);
        if (!$game->users->find($user)) {
            $game->users()->attach($user);
        }else {
            dd('user already in game!');
        }
    }
}
