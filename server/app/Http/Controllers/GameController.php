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
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class GameController extends Controller
{
    public function index(): array
    {
        return GameResource::collection(GameService::getGames())->resolve();
    }

    public function show(int $gameId): array
    {
        return GameResource::make(GameService::byId($gameId))->resolve();
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
            status: GameService::join($request->validated())
                ? StatusCodeInterface::STATUS_ACCEPTED
                : StatusCodeInterface::STATUS_FORBIDDEN
        );
    }

    public function quit(QuitRequest $request): Response
    {
        $data = $request->validated();

        return new Response(status: StatusCodeInterface::STATUS_CREATED);
    }

}
