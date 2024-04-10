<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Game\JoinRequest;
use App\Http\Requests\Game\QuitRequest;
use App\Http\Requests\Game\StoreRequest;
use App\Http\Resources\GameResource;
use App\Models\User;
use App\Services\GameService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(): array
    {
        return GameResource::collection(GameService::get())->resolve();
    }

    public function myGames(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return GameResource::collection($user->games())->resolve();
    }

    /**
     * @throws Exception
     */
    public function store(StoreRequest $request): array
    {
        return GameResource::make(GameService::create($request->validated()))->resolve();
    }

    public function join(JoinRequest $request): Response
    {
        return new Response(
            status: GameService::join($request->validated()) ? 201 : 403
        );
    }

    public function quit(QuitRequest $request): Response
    {
        $data = $request->validated();

        return new Response(status: 201);
    }

}
