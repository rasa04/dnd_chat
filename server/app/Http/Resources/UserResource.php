<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\CommonResolvesTrait;
use App\Models\User;
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
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $this->offsetGet('id'),
            'name' => $this->offsetGet('username'),
            'email' => $this->offsetGet('email'),
            'games' => $user->games(),
        ];
    }
}
