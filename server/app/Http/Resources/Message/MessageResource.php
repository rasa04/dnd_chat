<?php

declare(strict_types=1);

namespace App\Http\Resources\Message;

use App\Http\Resources\Traits\CommonResolvesTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    use CommonResolvesTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'from' => $this->user_id,
            'time' => $this->created_at->diffForHumans(),
        ];
    }
}
