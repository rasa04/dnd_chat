<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;

class GameController extends Controller
{
    public function index(): array
    {
        return GameResource::collection(Game::all())->resolve();
    }

    public function my_games()
    {
        return Game::all();
    }

    public function store()
    {
        return Game::all();
    }

    public function join()
    {

    }

}
