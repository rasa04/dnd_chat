<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\MessageTypeEnum;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\ObjectValue\HandleMessageTask;
use App\Queue\Enum\QueuesEnum;
use App\Repositories\MessagesRepository;
use App\Services\DiceRollService;
use App\Services\Queue\QueueInterface;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JsonException;
use Random\RandomException;

final class MessageController extends Controller
{
    public function __construct(
        private readonly QueueInterface $queueService,
        private readonly MessagesRepository $messagesRepository,
        private readonly DiceRollService $diceRollService
    ) {
    }

    public function index(int $gameId): array
    {
        return MessageResource::makeResolvedByCollection(
            $this->messagesRepository->findByGameId($gameId)
        );
    }

    public function store(StoreRequest $request): array|Response
    {
        $message = $request->validated();
        $message['user_id'] = Auth::id();

        if (
            isset($message['type'])
            && ((string)$message['type']) === MessagetypeEnum::DiceType->value
        ) {
            try {
                $message['body'] = json_encode(
                    $this->diceRollService->parseAndRoll($message['body']),
                    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
                );
            } catch (RandomException | JsonException) {
                return new Response(
                    'Failed to roll dices',
                    StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY
                );
            }
        }

        $message = $this->messagesRepository->createOne($message);

        $this->queueService->publish(
            HandleMessageTask::create(
                $message->getAttributeValue('body'),
                $message->getAttributeValue('game_id'),
                $message->getAttributeValue('user_id'),
                $message->getAttributeValue('type')
            ),
            QueuesEnum::HANDLE_MESSAGES
        );

        return MessageResource::makeResolvedByModel($message);
    }
}
