<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Throwable;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('admin')->only(['index', 'updateRole', 'destroy']);
    }

    public function index() {
        try {
            return response()->json(User::all());
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Error fetching users - ' . $e->getMessage(),
                code: $e->getCode() ?: 500
            );
        }
    }

    public function update(Request $request, $id) {
        try {
            $user = auth()->user();

            if ($user->id != $id) {
                return ApiResponse::error(
                    message: 'Access denied',
                    code: 403
                );
            }

            $request->validate([
                'name' => 'string',
                'email' => 'email|unique:users,email,' . $user->id,
            ]);

            $user->update($request->only('name', 'email'));

            return ApiResponse::success(message: 'Profile updated successfully', code: 200, data: $user);
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Error updating user - ' . $e->getMessage(),
                code: $e->getCode() ?: 500
            );
        }
    }

    public function updateRole(Request $request, $id) {
        try {
            $request->validate([
                'role' => 'required|string|exists:roles,name'
            ]);

            $user = User::findOrFail($id);
            $user->role = $request->role;
            $user->save();

            return ApiResponse::success(message: 'Role updated successfully', code: 200, data: $user);
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Error updating user role - ' . $e->getMessage(),
                code: $e->getCode() ?: 500
            );
        }
    }

    public function destroy($id) {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return ApiResponse::success(message: 'User deleted successfully');
        } catch (Throwable $e) {
            return ApiResponse::error(
                message: 'Error deleting user - ' . $e->getMessage(),
                code: $e->getCode() ?: 500
            );
        }
    }
}
