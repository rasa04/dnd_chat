<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResource
    {
        $credentials = $request->validated();

        $user = User::firstOrCreate([
            'username' => $credentials['username'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        return new AuthResource($user);
    }

    public function login(LoginRequest $request): JsonResource
    {
        $credentials = $request->validated();

        $user = ($request->has('username'))
            ? User::where('username', $credentials['username'])->first()
            : User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) throw ValidationException::withMessages([]);

        return new AuthResource($user);
    }

    public function logout(): array
    {
        Auth::user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }

    public function user(): array
    {
        return UserResource::make(Auth::user())->resolve();
    }
    public function users(): array
    {
        return UserResource::collection(User::all())->resolve();
    }
}
