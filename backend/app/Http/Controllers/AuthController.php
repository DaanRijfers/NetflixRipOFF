<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:password', // Ensure confirmPassword matches password
            'favoriteAnimal' => 'required|string|max:255', // Validate favorite animal(This has to change but for now I do it like this, this needs to be the profile image that get created with register)
        ]);

        $data = $request->all();
        if ($data['password'] !== $data['confirmPassword']) {
            return $this->respond(['message' => 'The confirm password field does not match.'], 422, $request);
        }

        // Create the user
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

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent!'], 200);
        } else {
            return response()->json(['message' => 'Failed to send reset link'], 400);
        }
    }

    // Helper function for generating JWT token
    protected function respondWithToken($token, $request)
    {
        return $this->respond([
            'message' => 'Logged in successfully!',
            'user' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ], 200, $request);
    }

    // General response helper
    protected function respond($data, $status = 200, $request = null)
    {
        return response()->json($data, $status);
    }
}