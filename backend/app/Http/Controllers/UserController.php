<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all users
    public function index(Request $request)
    {
        try {
            $users = User::all();
            return $this->respond(['message' => 'Users fetched succesfully!', 'users' => $users], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get a specific user
    public function show(Request $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            return $this->respond(['message' => 'User fetched succesfuly!', 'user' => $user], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(404, $request);
        }
    }

    // Update user information
    public function update(Request $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->update($request->all());
            return $this->respond(['message' => 'User updated successfully!', 'user' => $user], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Delete user
    public function destroy(Request $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();
            return $this->respond(['message' => 'User deleted successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}
