<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class AdminMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {

        if (auth()->user()?->role_id != '1') {
            return ApiResponse::error(
                message: 'Access denied' . auth()->user()->role_id,
                code: 403
            );
        }

        return $next($request);
    }
}
