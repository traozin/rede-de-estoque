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
                return ApiResponse::error(
                    message: 'Validation failed',
                    data: $validator->errors(),
                    code: 422
                );
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3, // Usuário comum
            ]);

            $token = JWTAuth::fromUser($user);

            return ApiResponse::success(
                message: 'User registered successfully',
                data: [
                    'user' => $user,
                    'token' => $token
                ],
                code: 201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Registration failed ' . $e->getMessage(),
                code: $e->getCode()
            );
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
                return ApiResponse::error(
                    message: 'Unauthorized',
                    code: 401
                );
            }

            return ApiResponse::success(
                message: 'Login successful',
                data: [
                    'token' => $token
                ],
                code: 200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Login failed ' . $e->getMessage(),
                code: $e->getCode()
            );
        }
    }

    /**
     * Get the authenticated User.
     * 
     * @return JsonResponse
     */
    public function me() {
        try {
            return ApiResponse::success(
                message: 'User fetched successfully',
                data: auth()->user()
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Failed to fetch user ' . $e->getMessage(),
                code: $e->getCode()
            );
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
            return ApiResponse::success(
                message: 'User logged out successfully',
                code: 200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Logout failed ' . $e->getMessage(),
                code: $e->getCode()
            );
        }
    }

    /**
     * Refresh a token.
     * 
     * @return JsonResponse
     */
    public function refresh() {
        try {
            return response()->json([
                'token' => auth()->refresh()
            ]);
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Token refresh failed ' . $e->getMessage(),
                code: $e->getCode()
            );
        }
    }
}
