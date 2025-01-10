<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Get all subscriptions
    public function index(Request $request)
    {
        try {
            $subscriptions = Subscription::all();
            return $this->respond($request, $subscriptions);
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

            $subscription = Subscription::create($request->all());
            return $this->respond($request, ['message' => 'Subscription created successfully!', 'subscription' => $subscription], 201);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Update a subscription
    public function update(Request $request, $subscription_id)
    {
        try {
            $subscription = Subscription::findOrFail($subscription_id);
            $subscription->update($request->all());

            return $this->respond($request, ['message' => 'Subscription updated successfully!', 'subscription' => $subscription]);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }

    // Delete a subscription
    public function destroy(Request $request, $subscription_id)
    {
        try {
            $subscription = Subscription::findOrFail($subscription_id);
            $subscription->delete();

            return $this->respond($request, ['message' => 'Subscription deleted successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithError(500, $request);
        }
    }
}
