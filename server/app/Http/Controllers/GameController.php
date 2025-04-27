<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\UnableToJoinTheGameException;
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
    public function __construct(
        private readonly GameService $gameService
    ) {
    }

    public function index(): array
    {
        return GameResource::makeResolvedByCollection($this->gameService->getGames());
    }

    public function show(int $gameId): array
    {
        return GameResource::makeResolvedByModel($this->gameService->byId($gameId));
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
        return GameResource::makeResolvedByModel(
            $this->gameService->create(
                $request->validated()
            )
        );
    }

    public function join(JoinRequest $request): Response|array
    {
        try {
            $game = $this->gameService->join($request->validated());
        } catch (UnableToJoinTheGameException $e) {
            return new Response($e->getMessage(), $e->getCode());
        }

        return GameResource::makeResolvedByModel($game);
    }

    public function quit(QuitRequest $request): Response
    {
        $request->validated();

        return new Response(status: StatusCodeInterface::STATUS_ACCEPTED);
    }

}
