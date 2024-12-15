<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return response()->json(User::all());
    }

    // Get a specific user
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user);
    }

    // Update user information
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $user->update($request->all());
        return response()->json(['message' => 'User updated successfully!', 'user' => $user]);
    }

    // Delete user
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully!']);
    }
}

