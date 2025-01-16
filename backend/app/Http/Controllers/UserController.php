<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Get all users
    public function index(Request $request)
    {
        try {
            $users = DB::select('CALL GetAllUsers()');
            return $this->respond(['message' => 'Users fetched successfully!', 'users' => $users], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Get a specific user
    public function show(Request $request, $user_id)
    {
        try {
            $user = DB::select('CALL GetUserById(?)', [$user_id]);
            return $this->respond(['message' => 'User fetched successfully!', 'user' => $user], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(404, $request);
        }
    }

    // Update user information
    public function update(Request $request, $user_id)
    {
        try {
            DB::statement('CALL UpdateUser(?, ?)', [$user_id, json_encode($request->all())]);
            $user = DB::select('CALL GetUserById(?)', [$user_id]);
            return $this->respond(['message' => 'User updated successfully!', 'user' => $user], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Delete user
    public function destroy(Request $request, $user_id)
    {
        try {
            DB::statement('CALL DeleteUser(?)', [$user_id]);
            return $this->respond(['message' => 'User deleted successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Assigns a subscription
    public function assignSubscription(Request $request, $user_id, $subscription_id)
    {
        try {
            $user = User::findOrFail($user_id);
            if($user->subscription_id != null) {
                return $this->respondWithError(405, $request);
            }
            if(!Subscription::where('id', '=', $subscription_id)) {
                return $this->respondWithError(404, $request);
            }
            $user->subscription_id = $subscription_id;
            $user->save();
            return $this->respond(['message' => 'User subscription updated successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Updates a subscription
    public function updateSubscription(Request $request, $user_id, $subscription_id)
    {
        try {
            $user = User::findOrFail($user_id);
            if($user->subscription_id == null) {
                return $this->respondWithError(405, $request);
            }
            if(!Subscription::where('id', '=', $subscription_id)) {
                return $this->respondWithError(404, $request);
            }
            $user->subscription_id = $subscription_id;
            $user->save();
            return $this->respond(['message' => 'User subscription updated successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Unassigns a subscription
    public function unassignSubscription(Request $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            if($user->subscription_id == null) {
                return $this->respondWithError(405, $request);
            }
            $user->subscription_id = null;
            $user->save();
            return $this->respond(['message' => 'User subscription unassigned successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}
