<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->respond(['message' => 'User registered successfully!', 'user' => $user], 201, $request);
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (!$token = auth()->attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
    
            if ($user) {
                $user->increment('failed_login_attempts');
                if ($user->failed_login_attempts >= 3) {
                    return response()->json(['message' => 'Account locked. Please reset your password.'], 423);
                }
                $user->save();
            }
    
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $user = auth()->user();
        $user->failed_login_attempts = 0;
        $user->save();

        return $this->respondWithToken($token);
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        return $this->respond(['message' => 'Logged out successfully!'], 200, $request);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->respondWithError($request, 404);
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? $this->respond(['message' => 'Password reset link sent!'], 200, $request)
            : $this->respondWithError($request, 400);
    }
}
