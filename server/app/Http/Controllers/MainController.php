<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Repositories\UsersRepository;

final class MainController extends Controller
{
    public function __construct(
        private readonly UsersRepository $usersRepository
    ) {
    }

    public function users(): array
    {
        return UserResource::makeResolvedByCollection($this->usersRepository->getAll());
    }
}
