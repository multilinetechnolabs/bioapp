<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\CoursePurchase;
use App\Models\CourseFreemiusTransaction;

class CourseSubscriptionController extends Controller
{
    /**
     * Cancel a course subscription (user-facing).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelSubscription(Request $request, $id)
    {
        $user = Auth::user();

        $coursePurchase = CoursePurchase::findOrFail($id);

        // Authorization: user can cancel their own course subscription, admins can cancel any
        if (!($user->isAdmin() || $coursePurchase->user_id == $user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($coursePurchase->status === 'cancelled') {
            return response()->json(['message' => 'Course subscription already cancelled'], 422);
        }

        // Attempt to find Freemius subscription id from transactions
        $tx = CourseFreemiusTransaction::where('user_id', $coursePurchase->user_id)
            ->where('course_id', $coursePurchase->course_id)
            ->where('status', 'paid')
            ->whereNotNull('freemius_transaction_id')
            ->orderBy('paid_at', 'desc')
            ->first();
        $freemiusSubId = $tx->freemius_transaction_id ?? null;
        if (empty($freemiusSubId)) {
            Log::warning('Freemius subscription id not found for course subscription', ['course_purchase_id' => $coursePurchase->id]);
            return response()->json(['message' => 'Freemius subscription id not found'], 422);
        }

        $productId = config('freemius.product_id');
        $bearerAuthorizationToken = config('freemius.bearer_authorization_token');
        $url = "https://api.freemius.com/v1/products/{$productId}/subscriptions/{$freemiusSubId}.json";

        try {
            Log::info('Calling Freemius cancel API', ['url' => $url, 'course_purchase_id' => $coursePurchase->id]);
           $response = Http::withOptions([
                        'verify' => false,
                    ])->withHeaders([
                        'Authorization' => 'Bearer ' . $bearerAuthorizationToken,
                        'Accept' => 'application/json',
                    ])->delete($url);

            Log::info('Freemius response', ['status' => $response->status(), 'body' => $response->body()]);
            if ($response->successful()) {
                $coursePurchase->status = 'cancelled';
                $coursePurchase->cancelled_at = now();
                $coursePurchase->save();

                return response()->json(['message' => 'Course subscription cancelled successfully']);
            } else {
                $body = $response->json() ?? ['error' => $response->body()];
                Log::error('Freemius cancel failed', ['response' => $body]);
                $message = $body['message'] ?? ($body['error'] ?? 'Freemius API error');
                return response()->json(['message' => $message], 422);
            }

        } catch (\Exception $e) {
            Log::error('Exception while cancelling course subscription', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
