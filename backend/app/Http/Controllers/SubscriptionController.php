<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Get all subscriptions
    public function index()
    {
        return response()->json(Subscription::all());
    }

    // Create a subscription
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string',
        ]);

        $subscription = Subscription::create($request->all());
        return response()->json(['message' => 'Subscription created successfully!', 'subscription' => $subscription], 201);
    }

    // Update a subscription
    public function update(Request $request, $subscription_id)
    {
        $subscription = Subscription::findOrFail($subscription_id);
        $subscription->update($request->all());

        return response()->json(['message' => 'Subscription updated successfully!', 'subscription' => $subscription]);
    }

    // Delete a subscription
    public function destroy($subscription_id)
    {
        $subscription = Subscription::findOrFail($subscription_id);
        $subscription->delete();

        return response()->json(['message' => 'Subscription deleted successfully!']);
    }
}

