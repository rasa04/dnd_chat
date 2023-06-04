<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(): array
    {
        $messages = Message::all();
        return MessageResource::collection($messages)->resolve();
    }
    public function store(StoreRequest $request): array
    {
        $data = $request->validated();
        $data['from'] = Auth::id();
        $message = Message::create($data);
        return MessageResource::make($message)->resolve();
    }
}
