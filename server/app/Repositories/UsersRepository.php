<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

final class UsersRepository extends AbstractRepository
{
    public function createOne(array $message): User
    {
        /** @var User */
        return User::query()->create($message);
    }

    public function getAll(): Collection
    {
        return User::query()->limit(self::MAX_LIMIT)->get();
    }
}
