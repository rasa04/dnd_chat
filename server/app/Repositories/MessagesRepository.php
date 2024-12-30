<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;

final class MessagesRepository extends AbstractRepository
{
    public function createOne(array $message): Message
    {
        /** @var Message */
        return Message::query()->create($message);
    }

    public function findByGameId(int $gameId): Collection
    {
        return Message::query()
            ->where('game_id', '=', $gameId)
            ->limit(self::MAX_LIMIT)
            ->get();
    }
}
