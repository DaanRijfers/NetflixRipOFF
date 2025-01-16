<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    // Get all subscriptions
    public function index(Request $request)
    {
        try {
            $subscriptions = DB::select('CALL GetAllSubscriptions()');
            return $this->respond(['message' => 'Successfully fetched subscriptions', 'subscriptions' => $subscriptions], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Create a subscription
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'plan' => 'required|string',
            ]);

            DB::statement('CALL CreateSubscription(?, ?)', [$request->user_id, $request->plan]);
            $subscription = DB::select('CALL GetSubscriptionById(?)', [DB::getPdo()->lastInsertId()]);
            return $this->respond(['message' => 'Subscription created successfully!', 'subscription' => $subscription], 201, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Update a subscription
    public function update(Request $request, $subscription_id)
    {
        try {
            DB::statement('CALL UpdateSubscription(?, ?)', [$subscription_id, json_encode($request->all())]);
            $subscription = DB::select('CALL GetSubscriptionById(?)', [$subscription_id]);
            return $this->respond(['message' => 'Subscription updated successfully!', 'subscription' => $subscription], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Delete a subscription
    public function destroy(Request $request, $subscription_id)
    {
        try {
            DB::statement('CALL DeleteSubscription(?)', [$subscription_id]);
            return $this->respond(['message' => 'Subscription deleted successfully!'], 200, $request);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}
