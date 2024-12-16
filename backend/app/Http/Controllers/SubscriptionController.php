<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SubscriptionController extends Controller
{
    // Get all subscriptions
    public function index(Request $request)
    {
        $subscriptions = Subscription::all();
        return $this->respond($request, $subscriptions);
    }

    // Create a subscription
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string',
        ]);

        $subscription = Subscription::create($request->all());
        return $this->respond($request, ['message' => 'Subscription created successfully!', 'subscription' => $subscription], 201);
    }

    // Update a subscription
    public function update(Request $request, $subscription_id)
    {
        $subscription = Subscription::findOrFail($subscription_id);
        $subscription->update($request->all());

        return $this->respond($request, ['message' => 'Subscription updated successfully!', 'subscription' => $subscription]);
    }

    // Delete a subscription
    public function destroy(Request $request, $subscription_id)
    {
        $subscription = Subscription::findOrFail($subscription_id);
        $subscription->delete();

        return $this->respond($request, ['message' => 'Subscription deleted successfully!']);
    }

    // Helper function to respond in JSON or CSV format
    private function respond(Request $request, $data, $status = 200)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'text/csv') {
            $csvData = $this->toCsv($data);
            return Response::make($csvData, $status, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="subscriptions.csv"',
            ]);
        }

        return response()->json($data, $status);
    }

    // Helper function to convert data to CSV format
    private function toCsv($data)
    {
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            $csv = implode(',', array_keys($data[0])) . "\n";
            foreach ($data as $row) {
                $csv .= implode(',', array_values($row)) . "\n";
            }
        } else {
            $csv = implode(',', array_keys($data)) . "\n";
            $csv .= implode(',', array_values($data)) . "\n";
        }

        return $csv;
    }
}
