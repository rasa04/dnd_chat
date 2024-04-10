<?php

declare(strict_types=1);

use App\Http\Controllers\GameController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(MainController::class)->group(function () {
    Route::get('/users', 'users');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(MessageController::class)->group(function () {
        Route::get('/messages', 'index');
        Route::post('/messages', 'store');
    });
    Route::controller(AuthController::class)->group(function() {
        Route::get('/user', 'user');
        Route::post('/logout', 'logout');
    });
    Route::prefix('/game')->group(function () {
        Route::get('/', [GameController::class, 'index']);
        Route::post('/', [GameController::class, 'store']);
        Route::post('/my', [GameController::class, 'myGames']);
        Route::post('/join', [GameController::class, 'join']);
    });
});

Route::get('/test', static fn () => ['ack']);
