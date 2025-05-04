<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\CommonResolvesTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class GameResource extends JsonResource
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
            'id' => $this->offsetGet('id'),
            'name' => $this->offsetGet('name'),
            'game_master_id' => $this->offsetGet('game_master_id'),
            'description' => $this->offsetGet('description'),
            'photo_link' => $this->offsetGet('photo_link'),
            'participants' => UserResource::makeResolvedByCollection($this->offsetGet('users')),
        ];
    }
}
