<?php

use App\Http\Controllers\Auth\LoginController;
use App\Helpers\RouteMiddlewares;
use App\Http\Controllers\DashboardController;

Route::get('/', [LoginController::class, 'index']);

Route::group(['middleware' => RouteMiddlewares::JWT_AUTH], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});