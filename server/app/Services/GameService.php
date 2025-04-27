<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Game;
use App\Repositories\GameRepository;
use App\Repositories\RepositoryInterface;
use App\Exceptions\UnableToJoinTheGameException;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

final readonly class GameService
{
    public function __construct(
        private GameRepository $gameRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Builder|Model
    {
        return $this->gameRepository->createOneAndAttachUserById($data, Auth::id());
    }

    /**
     * @throws UnableToJoinTheGameException
     */
    public function join(array $data): Game
    {
        $game = $this->gameRepository->findOneById((int)$data['id']);
        if (is_null($game)) {
            throw new UnableToJoinTheGameException(
                'Game not found',
                StatusCodeInterface::STATUS_NOT_FOUND
            );
        }

        $user = Auth::user();
        if ($game->users->find($user) !== null) {
            throw new UnableToJoinTheGameException(
                sprintf(
                    'Game [%d] already joined for user [%d]',
                    $game->getAttributeValue('id'),
                    $user->getAuthIdentifier()
                ),
                StatusCodeInterface::STATUS_FORBIDDEN
            );
        }

        $game->users()->attach($user);

        return $game;
    }

    public function getGames(int $limit = RepositoryInterface::MAX_LIMIT): Collection
    {
        return $this->gameRepository->findMany($limit);
    }

    public function byId(int $id): ?Game
    {
        return $this->gameRepository->findOneById($id);
    }
}
