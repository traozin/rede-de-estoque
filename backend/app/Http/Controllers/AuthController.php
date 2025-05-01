<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Http\JsonResponse;
use Throwable;

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
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user', 'token'), 201);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Registration failed ' . $e->getMessage()], $e->getCode() ?: 500);
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
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(compact('token'));
        } catch (Throwable $e) {
            return response()->json(['error' => 'Login failed ' . $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    /**
     * Get the authenticated User.
     * 
     * @return JsonResponse
     */
    public function me() {
        try {
            return response()->json(auth()->user());
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to fetch user ' . $e->getMessage()], $e->getCode() ?: 500);
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
            return response()->json(['message' => 'Successfully logged out']);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Logout failed ' . $e->getMessage()], $e->getCode() ?: 500);
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
            return response()->json(['error' => 'Token refresh failed ' . $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
