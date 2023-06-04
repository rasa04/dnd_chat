<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\UserResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function users(): array
    {
        return UserResource::collection(User::all())->resolve();
    }
    public function games(): array
    {
        return GameResource::collection(Game::all())->resolve();
    }
}
