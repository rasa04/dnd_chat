<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

final class UsersRepository extends AbstractRepository
{
    public function firstOrCreateOne(array $user): User
    {
        /** @var User */
        return User::query()->firstOrCreate($user);
    }

    public function getAll(): Collection
    {
        return User::query()->limit(self::MAX_LIMIT)->get();
    }
}
