<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
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
    Route::get('/games', 'games');
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
});

Route::get('/test', function () {
   return [];
});
