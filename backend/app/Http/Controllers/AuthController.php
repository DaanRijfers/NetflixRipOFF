<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->respond(['message' => 'User registered successfully!', 'user' => $user], 201, $request);
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return $this->respond(['message' => 'Login successful!'], 200, $request);
        } 
        $user = User::where('email', $request->email)->first();

        if ($user){
            $user->increment('login_attempts');

            if ($user->login_attempts >= 3){
                return $this->respond(['message' => 'Account locked. Please reset your password.'], 423, $request);
            }

            $user->save();
        }
        return $this->respond(['message' => 'Invalid credentials'], 401, $request);
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

        return $status === Password::RESET_LINK_SENT
            ? $this->respond(['message' => 'Password reset link sent!'], 200, $request)
            : $this->respond(['message' => 'Failed to send reset link'], 400, $request);
    }

    // Helper function to respond in JSON or CSV format
    private function respond(array $data, int $status, Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'text/csv') {
            $csvData = $this->arrayToCsv($data);
            return response($csvData, $status)->header('Content-Type', 'text/csv');
        }

        return response()->json($data, $status);
    }

    // Helper function to convert array to CSV
    private function arrayToCsv(array $data)
    {
        $csv = '';
        $header = false;

        foreach ($data as $key => $value) {
            if (!$header) {
                $csv .= implode(',', array_keys($data)) . "\n";
                $header = true;
            }
            $csv .= implode(',', array_map('strval', array_values($data))) . "\n";
        }

        return $csv;
    }
}
