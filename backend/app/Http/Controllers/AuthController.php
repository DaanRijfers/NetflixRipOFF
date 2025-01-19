<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use App\Models\User;

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
        ]);

        $data = $request->all();
        if ($data['password'] !== $data['confirmPassword']) {
            return $this->respondWithError('The confirm password field does not match.', 422, $request);
        }

        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        DB::statement('CALL RegisterUser(?, ?, @message)', [$email, $password]);
        $message = DB::select('SELECT @message AS message')[0]->message;

        return $this->respond(['message' => $message], 200, $request);
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->increment('failed_login_attempts');
                if ($user->failed_login_attempts >= 3) {
                    return $this->respondWithError('Account locked. Please reset your password.', 423, $request);
                }
                $user->save();
            }

            return $this->respondWithError('Invalid credentials', 401, $request);
        }

        $user = auth('api')->user();
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

    // Helper function for generating JWT token
    protected function respondWithToken($token, $request)
    {
        return $this->respond([
            'message' => 'Logged in successfully!',
            'user' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => auth('api')->user(),
            ],
        ], 200, $request);
    }
}