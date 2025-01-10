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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->respond(['message' => 'User registered successfully!', 'user' => $user], 201, $request);
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (!$token = auth()->attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
    
            if ($user) {
                $user->increment('failed_login_attempts');
                if ($user->failed_login_attempts >= 3) {
                    return $this->respond(['message' => 'Account locked. Please reset your password.'], 423, $request);
                }
                $user->save();
            }
    
            return $this->respond(['message' => 'Invalid credentials'], 401, $request);
        }
    
        $user = auth()->user();
        $user->failed_login_attempts = 0;
        $user->save();

        return $this->respondWithToken($token, $request);
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
            return $this->respondWithError(404, $request);
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? $this->respond(['message' => 'Password reset link sent!'], 200, $request)
            : $this->respondWithError(400, $request);
    }

    // Helper function for generating JWT token
    protected function respondWithToken($token, $request)
    {
        return $this->respond([
            'message' => 'Logged in succesfully!',
            'user' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
        ]], 200, $request);
    }
}
