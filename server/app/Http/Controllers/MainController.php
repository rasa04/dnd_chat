<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class MainController extends Controller
{
    public function users(): array
    {
        return UserResource::collection(User::all())->resolve();
    }
}
