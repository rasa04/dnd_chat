<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\IncorrectMessageWorkloadException;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\ObjectValue\HandleMessageTask;
use App\Queue\Enum\QueuesEnum;
use App\Services\Queue\QueueInterface;
use Illuminate\Support\Facades\Auth;

final class MessageController extends Controller
{
    private QueueInterface $queueService;

    public function __construct(QueueInterface $queueService)
    {
        $this->queueService = $queueService;
    }

    public function index(int $gameId): array
    {
        return MessageResource::collection(
            Message::query()->where('game_id', '=', $gameId)->get()
        )->resolve();
    }

    /**
     * @throws IncorrectMessageWorkloadException
     */
    public function store(StoreRequest $request): array
    {
        $message = $request->validated();
        $message['user_id'] = Auth::id();

        $this->queueService->publish(
            HandleMessageTask::fromArray($message),
            QueuesEnum::HANDLE_MESSAGES
        );

        return MessageResource::make(Message::query()->create($message))->resolve();
    }
}
