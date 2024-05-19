<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Queue\Enum\QueuesEnum;
use App\Services\Queue\RabbitService;
use Illuminate\Support\Facades\Auth;

final class MessageController extends Controller
{
    public function index(int $gameId): array
    {
        return MessageResource::collection(
            Message::query()->where('game_id', '=', $gameId)->get()
        )->resolve();
    }

    public function store(StoreRequest $request): array
    {
        $message = $request->validated();
        $message['user_id'] = Auth::id();
        (new RabbitService())->publish(['message' => $message], QueuesEnum::HANDLE_MESSAGES);

        return MessageResource::make(Message::query()->create($message))->resolve();
    }
}
