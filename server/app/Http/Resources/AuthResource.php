<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
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
            'user' => new UserResource($this),
            'token' => $user
                ->createToken('dnd', expiresAt: CarbonImmutable::now()->addDay())
                ->plainTextToken
        ];
    }
}
