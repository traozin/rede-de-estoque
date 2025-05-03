<?php

namespace App\Helpers;

class RouteMiddlewares {
    public const JWT_AUTH = \App\Http\Middleware\JwtAuthenticate::class;
    public const ADMIN = \App\Http\Middleware\AdminMiddleware::class;
    public const ROLE = \App\Http\Middleware\RoleMiddleware::class;
}
