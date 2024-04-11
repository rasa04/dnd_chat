<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    GameController,
    MainController,
    MessageController
};

Route::controller(MainController::class)->group(function () {
    Route::get('/users', 'users');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(MessageController::class)->group(function () {
        Route::get('/messages/{id}', 'index');
        Route::post('/messages', 'store');
    });
    Route::controller(AuthController::class)->group(function() {
        Route::get('/user', 'user');
        Route::post('/logout', 'logout');
    });
    Route::prefix('/game')->group(function () {
        Route::get('/', [GameController::class, 'index']);
        Route::get('/{id}', [GameController::class, 'show']);
        Route::post('/', [GameController::class, 'store']);
        Route::post('/my', [GameController::class, 'myGames']);
        Route::post('/join', [GameController::class, 'join']);
    });
});

Route::get('/test', static fn () => ['ack']);
