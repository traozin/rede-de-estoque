<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Throwable;

class RoleMiddleware {

    public const ROLE = 'role';

    public static function register(): void {
        app('router')->aliasMiddleware(self::ROLE, \App\Http\Middleware\RoleMiddleware::class);
    }

    /**
     * Handle an incoming request with required roles.
     */
    public function handle(Request $request, Closure $next, ...$roles) {
        try {
            $user = auth()->user();

            if (!$user || !$user->role) {
                return ApiResponse::error(
                    message: 'Unauthorized - user or role not found',
                    code: 404
                );
            }

            if (!in_array($user->role_id, $roles)) {
                return ApiResponse::error(
                    message: 'Access denied for role: ' . $user->role->name,
                    code: 403
                );
            }

            return $next($request);
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Error in RoleMiddleware - ' . $e->getMessage(),
                code: $e->getCode() ?: 500
            );
        }
    }
}
