<?php

namespace App\Helpers;

class RouteMiddlewares {

    public const JWT_AUTH = \App\Http\Middleware\JwtAuthenticate::class;
    public const ADMIN = \App\Http\Middleware\AdminMiddleware::class;
}
