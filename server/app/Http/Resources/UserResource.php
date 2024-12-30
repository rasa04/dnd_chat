<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\CommonResolvesTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->username,
            'email' => $this->email,
            'games' => $this->games()
        ];
    }
}
