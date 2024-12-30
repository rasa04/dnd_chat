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
        return GameResource::makeResolvedByCollection(GameService::getGames());
    }

    public function show(int $gameId): array
    {
        return GameResource::makeResolvedByModel(GameService::byId($gameId));
    }

    public function myGames(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return GameResource::makeResolvedByCollection($user->games());
    }

    /**
     * @throws Exception
     */
    public function store(StoreRequest $request): array
    {
        return GameResource::makeResolvedByModel(GameService::create($request->validated()));
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

        return new Response(status: StatusCodeInterface::STATUS_ACCEPTED);
    }

}
