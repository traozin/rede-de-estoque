<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('admin')->only(['index', 'updateRole', 'destroy']);
    }

    public function index() {
        try {
            return response()->json(User::all());
        } catch (Throwable $e) {
            return ApiResponse::error(message: 'Error fetching users - ' . $e->getMessage(), code: $e->getCode());
        }
    }

    public function update(Request $request, $id) {
        try {
            $user = auth()->user();

            if ($user->id != $id) {
                return ApiResponse::error('Access denied', 403);
            }

            $request->validate([
                'name' => 'string',
                'email' => 'email|unique:users,email,' . $user->id,
            ]);

            $user->update($request->only('name', 'email'));

            return ApiResponse::success('Profile updated successfully', 200, $user);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', 422, $e->errors());
        } catch (Throwable $e) {
            return ApiResponse::error('Error updating user - ' . $e->getMessage(), code: $e->getCode());
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

            return ApiResponse::success('Role updated successfully', 200, $user);
        } catch (Throwable $e) {
            return ApiResponse::error('Error updating user role - ' . $e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id) {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return ApiResponse::success('User deleted successfully');
        } catch (Throwable $e) {
            return ApiResponse::error('Error deleting user - ' . $e->getMessage(), $e->getCode());
        }
    }
}
