<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\ApiResponse;

class JwtAuthenticate {

    /**
     * Handle an incoming request.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     */
    public function handle(Request $request, Closure $next) {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return ApiResponse::error(
                    message: 'User not found',
                    code: 404
                );
            }

            return $next($request);
        } catch (JWTException $e) {
            return ApiResponse::error(
                message: 'Token is invalid or expired - ' . $e->getMessage(),
                code: 401
            );
        }
    }
}