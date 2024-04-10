<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResource
    {
        return new AuthResource(User::query()->firstOrCreate($request->validated()));
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
        Auth::user()->currentAccessToken()->delete();

        return new Response(status: 201);
    }

    public function user(): array
    {
        return UserResource::make(Auth::user())->resolve();
    }
}
