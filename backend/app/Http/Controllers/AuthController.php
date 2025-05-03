<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Http\JsonResponse;
use Throwable;
use App\Helpers\ApiResponse;

class AuthController extends Controller {

    /**
     * Register a new user.
     * 
     * @param Request $request
     * @return mixed|JsonResponse
     */
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3, // Usuário comum
            ]);

            $token = JWTAuth::fromUser($user);

            return ApiResponse::success(
                'User registered successfully',
                201,
                [
                    'user' => $user,
                    'token' => $token
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Registration failed ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Login a user and return a JWT token.
     * 
     * @param Request $request
     * @return mixed|JsonResponse
     */
    public function login(Request $request) {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponse::error('Unauthorized', 401);
            }

            return ApiResponse::success(
                'Login successful',
                200,
                [
                    'token' => $token
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Login failed ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Get the authenticated User.
     * 
     * @return JsonResponse
     */
    public function me() {
        try {
            return ApiResponse::success('User fetched successfully', 200, auth()->user());
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to fetch user ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Logout the user (Invalidate the token).
     * 
     * @return JsonResponse
     */
    public function logout() {
        try {
            auth()->logout();
            return ApiResponse::success('User logged out successfully', 200);
        } catch (Throwable $e) {
            return ApiResponse::error('Logout failed ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Refresh a token.
     * 
     * @return JsonResponse
     */
    public function refresh() {
        try {
            return ApiResponse::success(
                'Token refreshed successfully',
                200,
                [
                    'token' => auth()->refresh()
                ]
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                'Token refresh failed ' . $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
