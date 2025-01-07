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

    // Helper function to respond in JSON or CSV format
    private function respond(array $data, int $status, Request $request)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'text/csv':
                $csvData = $this->convertToCsv($data);
                return response($csvData, $status)->header('Content-Type', 'text/csv');
            case 'text/xml':
                $xmlData = $this->arrayToXml($data);
                return response($xmlData, $status)->header('Content-Type', 'text/xml');
            default:
                return response()->json($data, $status);
        }
    }

    // Helper function to convert data to CSV format
    private function convertToCsv($data)
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

    // Helper function for generating JWT token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    // Handle errors and generate appropriate error code
    private function handleError(int $status): string
    {
        $errorMessages = $this->getHttpErrorMessages();
        return $errorMessages[$status] ?? 'An error occurred';
    }

    // Get all HTTP error messages
    private function getHttpErrorMessages(): array
    {
        return [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            208 => 'Already Reported',
            226 => 'IM Used',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I’m a teapot',
            421 => 'Misdirected Request',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Too Early',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
        ];
    }
}
