<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\JoinRequest;
use App\Http\Requests\Game\QuitRequest;
use App\Http\Requests\Game\StoreRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(): array
    {
        return GameResource::collection(Game::all())->resolve();
    }

    public function my_games()
    {
        $user = Auth::user();
        return GameResource::collection($user->games())->resolve();
    }

    public function store(StoreRequest $request): array
    {
        $data = $request->validated();
        $game = GameService::create($data);
        return GameResource::make($game)->resolve();
    }

    public function join(JoinRequest $request)
    {
        $data = $request->validated();
        GameService::join($data);
        return response('success');
    }

    public function quit(QuitRequest $request)
    {
        $data = $request->validated();
    }

}
