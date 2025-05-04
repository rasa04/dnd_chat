<?php

declare(strict_types=1);

namespace App\Http\Resources\Message;

use App\Http\Resources\Traits\CommonResolvesTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class MessageResource extends JsonResource
{
    use CommonResolvesTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        /** @var Carbon $time */
        $time = $this->offsetGet('created_at');

        return [
            'id' => (string)$this->offsetGet('id'),
            'body' => $this->offsetGet('body'),
            'type' => $this->offsetGet('type'),
            'from' => (string)$this->offsetGet('user_id'),
            'time' => $time->toIso8601String(),
        ];
    }
}
