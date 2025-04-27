<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\ObjectValue\HandleMessageTask;
use App\Queue\Enum\QueuesEnum;
use App\Repositories\MessagesRepository;
use App\Services\Queue\QueueInterface;
use Illuminate\Support\Facades\Auth;

final class MessageController extends Controller
{
    public function __construct(
        private readonly QueueInterface $queueService,
        private readonly MessagesRepository $messagesRepository
    ) {
    }

    public function index(int $gameId): array
    {
        return MessageResource::makeResolvedByCollection(
            $this->messagesRepository->findByGameId($gameId)
        );
    }

    public function store(StoreRequest $request): array
    {
        $message = $request->validated();
        $message['user_id'] = Auth::id();

        $this->queueService->publish(
            HandleMessageTask::create(
                (string)$message['body'],
                (int)$message['game_id'],
                (int)$message['user_id']
            ),
            QueuesEnum::HANDLE_MESSAGES
        );

        return MessageResource::makeResolvedByModel(
            $this->messagesRepository->createOne($message)
        );
    }
}
