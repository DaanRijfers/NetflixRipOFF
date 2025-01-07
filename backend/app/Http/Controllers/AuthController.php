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

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();
            $user->update(['login_attempts' => 0]); // Reset login attempts on success
            return $this->respond(['message' => 'Login successful!'], 200, $request);
        }

        $user = User::where('username', $request->username)->first();
        if ($user) {
            $user->increment('login_attempts');

            if ($user->login_attempts >= 3) {
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

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->respond(['message' => 'Email not found.'], 404, $request);
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? $this->respond(['message' => 'Password reset link sent!'], 200, $request)
            : $this->respond(['message' => 'Failed to send reset link'], 400, $request);
    }

    // Helper function to respond in JSON or CSV format
    private function respond(array $data, int $status, Request $request)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'text/csv':
                $csvData = $this->arrayToCsv($data);
                return response($csvData, $status)->header('Content-Type', 'text/csv');
            case 'text/xml':
                $xmlData = $this->arrayToXml($data);
                return response($xmlData, $status)->header('Content-Type', 'text/xml');
            default:
                return response()->json($data, $status);
        }
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

    // Helper function to convert array to XML
    private function arrayToXml(array $data, \SimpleXMLElement $xmlData = null): string
    {
        if ($xmlData === null) {
            $xmlData = new \SimpleXMLElement('<users/>');
        }

        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? "item$key" : $key;
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars("$value"));
            }
        }

        return $xmlData->asXML();
    }
}