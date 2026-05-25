<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\FreemiusTransaction;

class PlanController extends Controller
{
    public function subscribe(Request $request)
    {
        if (Auth::user()->hasValidSubscription()) {
            return response()->json([
                'success' => false,
                'transaction_id' => '',
                'message' => 'You still have a valid subscription. Action Forbidden.'
            ]);
        }

        $plan = Plan::findOrFail($request->plan_id);
        if ($plan->category === 'monthly') {
            $planId = config('freemius.monthly_plan_id');
        } elseif ($plan->category === 'yearly') {
            $planId = config('freemius.yearly_plan_id');
        } else {
            return response()->json([
                'success' => false,
                'transaction_id' => '',
                'message' => 'Invalid Subscription Plan'
            ]);
        }

        $productId = config('freemius.product_id');
        $publicKey = config('freemius.public_key');
        $secretKey = config('freemius.secret_key');
        $sandbox = config('freemius.sandbox');

        if (empty($planId) || empty($productId) || empty($publicKey) || empty($secretKey)) {
            return response()->json([
                'success' => false,
                'transaction_id' => '',
                'message' => 'Payment gateway is not configured properly.'
            ]);
        }

        $transaction = FreemiusTransaction::create([
            'user_id' => auth()->id(),
            'plan_id' => $request->plan_id,
            'amount' => $plan->price,
            'currency' => 'USD',
            'status' => 'pending',
            'customer_email' => auth()->user()->email,
        ]);

        $timestamp = time();
        $sandboxToken = md5(
            $timestamp .
            $productId .
            $secretKey .
            $publicKey .
            'checkout'
        );

        $return = [
            'success' => true,
            'transaction_id' => $transaction->id,
            'product_id' => $productId,
            'plan_id' => $planId,
            'public_key' => $publicKey,
            'purchase_name' => 'Anew Avenue Subscription',
            'licenses' => 1,
            'image' => url('/favicon.ico'),
            'sandbox' => $sandbox
        ];

        if($sandbox){
            $return['sandbox_token'] = $sandboxToken;
            $return['sandbox_ctx'] = $timestamp;
        }

        return response()->json($return);
    }

    public function success(Request $request)
    {
        $payload = $request->all();

        \Log::info('Freemius Success Callback', $payload);

        /*
        |--------------------------------------------------------------------------
        | CLIENT RESPONSE
        |--------------------------------------------------------------------------
        */

        $response = $payload['response'] ?? [];

        $purchase = $response['purchase'] ?? [];

        $user = $response['user'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        $transactionId = $purchase['id'] ?? null;

        $subscriptionId = $purchase['external_id'] ?? null;

        $licenseId = $purchase['license_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | FIND TRANSACTION
        |--------------------------------------------------------------------------
        */

        $transaction = FreemiusTransaction::find(
            $payload['transaction_id']
        );

        if (! $transaction) {

            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE
        |--------------------------------------------------------------------------
        */

        if ($transaction->status === 'paid') {

            return response()->json([
                'success' => true,
                'message' => 'Already processed'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $transaction->update([

            'status' => 'paid',

            'freemius_transaction_id' => $transactionId,

            'freemius_subscription_id' => $subscriptionId,

            'freemius_license_key' => $licenseId,

            'payload' => $payload,

            'paid_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE SUBSCRIPTION
        |--------------------------------------------------------------------------
        */

        $plan = Plan::findOrFail($transaction->plan_id);

        $subscription = Subscription::create([

            'user_id' => $transaction->user_id,

            'plan_id' => $transaction->plan_id,

            'starts_at' => Carbon::now(),

            'ends_at' => $plan->category == 'monthly'
                ? Carbon::now()->addDays(30)
                : Carbon::now()->addYear()
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment = new \App\Models\Payment([

            'user_id' => $transaction->user_id,

            'amount' => $purchase['initial_amount']
                ?? $plan->price,

            'date_paid' => Carbon::now()
        ]);

        $payment->resource_id = $subscription->id;

        $payment->resource_type = Subscription::class;

        $payment->description = $plan->description;

        $payment->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function failed(Request $request)
    {
        $payload = $request->all();

        \Log::info('Freemius Failed Callback', $payload);

        /*
        |--------------------------------------------------------------------------
        | FIND TRANSACTION
        |--------------------------------------------------------------------------
        */

        $transaction = FreemiusTransaction::find(
            $payload['transaction_id']
        );

        if (! $transaction) {

            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE
        |--------------------------------------------------------------------------
        */

        if ($transaction->status === 'paid') {

            return response()->json([
                'success' => false,
                'message' => 'Transaction already paid'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE FAILED STATUS
        |--------------------------------------------------------------------------
        */

        $transaction->update([

            'status' => 'failed',

            'payload' => $payload
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Payment marked as failed'

        ]);
    }
}
