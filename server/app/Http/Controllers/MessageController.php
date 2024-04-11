<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
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
        return MessageResource::make(
            Message::query()->create([
                'user_id' => Auth::id(),
                ...$request->validated(),
            ])
        )->resolve();
    }
}
