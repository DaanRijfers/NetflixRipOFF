<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return $this->respondWithFormat(User::all());
    }

    // Get a specific user
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return $this->respondWithFormat($user);
    }

    // Update user information
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $user->update($request->all());
        return $this->respondWithFormat(['message' => 'User updated successfully!', 'user' => $user]);
    }

    // Delete user
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return $this->respondWithFormat(['message' => 'User deleted successfully!']);
    }

    // Determine response format based on Accept header
    private function respondWithFormat($data, $status = 200)
    {
        $acceptHeader = request()->header('Accept');

        if ($acceptHeader === 'text/csv') {
            $csvData = $this->convertToCsv($data);
            return response($csvData, $status)->header('Content-Type', 'text/csv');
        }

        return response()->json($data, $status);
    }

    // Convert data to CSV format
    private function convertToCsv($data)
    {
        if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            $data = $data->toArray();
        }

        $csv = '';
        $header = false;

        foreach ($data as $row) {
            if (!$header) {
                $csv .= implode(',', array_keys($row)) . "\n";
                $header = true;
            }
            $csv .= implode(',', array_values($row)) . "\n";
        }

        return $csv;
    }
}
