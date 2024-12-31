<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Game;
use App\Repositories\GameRepository;
use App\Repositories\RepositoryInterface;
use Exception;
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
     * @param array $data
     * @return bool true - if joined, false - if already joined or game does not exist
     */
    public function join(array $data): bool
    {
        $user = Auth::user();
        /** @var Game $game */
        if (
            !empty($game = $this->gameRepository->findOneById((int)$data['id']))
            && is_null($game->users->find($user))
        ) {
            $game->users()->attach($user);

            return true;
        }

        return false;
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
