<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\RouteMiddlewares;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => RouteMiddlewares::JWT_AUTH], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);

    Route::get('/produtos', [ProductController::class, 'index']);
    Route::get('/produtos/{id}', [ProductController::class, 'show']);

    // rotas apenas pra admin
    Route::middleware([RouteMiddlewares::ROLE . ':1'])->group(function () {
        Route::get('/usuarios', [UserController::class, 'index']);
        Route::patch('/usuarios/{id}/role', [UserController::class, 'updateRole']);
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);

        Route::post('/produtos', [ProductController::class, 'store']);
        Route::delete('/produtos/{id}', [ProductController::class, 'destroy']);
    });
    
    // rotas apenas pra admin e operador
    Route::middleware(RouteMiddlewares::ROLE . ':1,2')->group(function () {
        Route::put('/produtos/{id}', [ProductController::class, 'update']);
    });
});