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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo_link' => $this->photo_link,
            'participants' => UserResource::collection($this->users)->resolve()
        ];
    }
}
