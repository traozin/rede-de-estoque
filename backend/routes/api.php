<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Helpers\RouteMiddlewares;


Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
});

// rotas apenas pra admin
Route::group(['middleware' => ['jwt.auth', RouteMiddlewares::ADMIN]], function () {
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::patch('/usuarios/{id}/role', [UserController::class, 'updateRole']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
});