<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Subscription;
use App\Models\FreemiusTransaction;

class SubscriptionController extends Controller
{
    /**
     * Cancel a subscription (user-facing).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelSubscription(Request $request, $id)
    {
        $user = Auth::user();

        $subscription = Subscription::findOrFail($id);

        // Authorization: user can cancel their own subscription, admins can cancel any
        if (!($user->isAdmin() || $subscription->user_id == $user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($subscription->status === 'cancelled') {
            return response()->json(['message' => 'Subscription already cancelled'], 422);
        }

        // Attempt to find Freemius subscription id from transactions
        $tx = FreemiusTransaction::where('user_id', $subscription->user_id)
            ->where('plan_id', $subscription->plan_id)
            ->where('status', 'paid')
            ->whereNotNull('freemius_transaction_id')
            ->orderBy('paid_at', 'desc')
            ->first();
        $freemiusSubId = $tx->freemius_transaction_id ?? null;
        if (empty($freemiusSubId)) {
            Log::warning('Freemius subscription id not found for subscription', ['subscription_id' => $subscription->id]);
            return response()->json(['message' => 'Freemius subscription id not found'], 422);
        }

        $productId = config('freemius.product_id');
        $bearerAuthorizationToken = config('freemius.bearer_authorization_token');
        $url = "https://api.freemius.com/v1/products/{$productId}/subscriptions/{$freemiusSubId}.json";

        try {
            Log::info('Calling Freemius cancel API', ['url' => $url, 'subscription_id' => $subscription->id]);
           $response = Http::withOptions([
                        'verify' => false,
                    ])->withHeaders([
                        'Authorization' => 'Bearer ' . $bearerAuthorizationToken,
                        'Accept' => 'application/json',
                    ])->delete($url);

            Log::info('Freemius response', ['status' => $response->status(), 'body' => $response->body()]);
            if ($response->successful()) {
                $subscription->status = 'cancelled';
                $subscription->cancelled_at = now();
                $subscription->save();

                return response()->json(['message' => 'Subscription cancelled successfully']);
            } else {
                $body = $response->json() ?? ['error' => $response->body()];
                Log::error('Freemius cancel failed', ['response' => $body]);
                $message = $body['message'] ?? ($body['error'] ?? 'Freemius API error');
                return response()->json(['message' => $message], 422);
            }

        } catch (\Exception $e) {
            Log::error('Exception while cancelling subscription', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
