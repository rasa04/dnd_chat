<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UsersRepository;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

final class AuthController extends Controller
{
    public function __construct(
        private readonly UsersRepository $usersRepository
    ) {
    }

    public function register(RegisterRequest $request): JsonResource
    {
        return new AuthResource($this->usersRepository->firstOrCreateOne($request->validated()));
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): array
    {
        $credentials = $request->validated();
        $authType = isset($credentials['username']) ? 'username' : 'email';

        if (is_null($user = User::query()->firstWhere($authType, '=', $credentials[$authType]))) {
            throw ValidationException::withMessages(['User not found']);
        }
        if (!Hash::check($credentials['password'], $user->getAttribute('password'))) {
            throw ValidationException::withMessages(['Incorrect password']);
        }

        return AuthResource::make($user)->resolve();
    }

    public function logout(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        $token->delete();

        return new Response(status: StatusCodeInterface::STATUS_NO_CONTENT);
    }

    public function user(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return UserResource::makeResolvedByModel($user);
    }
}
