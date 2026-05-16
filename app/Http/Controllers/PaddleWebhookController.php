<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PaddleTransaction;
use App\Models\Plan;
use App\Models\Subscription;

class PaddleWebhookController extends Controller
{
    public function handle(Request $request)
    {

        $payload = $request->all();

        // LOG FOR DEBUGGING

        \Log::info('Paddle Webhook', $payload);

        // EVENT TYPE

        $eventType = $payload['event_type']
            ?? $payload['eventType']
            ?? null;

        // EVENT DATA

        $data = $payload['data'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION COMPLETED
        |--------------------------------------------------------------------------
        */

        if ($eventType === 'transaction.completed')
        {
            $transactionId = $data['id'] ?? null;

            if ($transactionId)
            {
                $transaction = PaddleTransaction::where(
                    'paddle_transaction_id',
                    $transactionId
                )->first();

                if ($transaction)
                {
                    $transaction->update([

                        'status' => 'paid',

                        'payload' => $payload,

                        'paid_at' => now(),

                        'paddle_customer_id' => $data['customer_id'] ?? null,

                        'paddle_subscription_id' => $data['subscription_id'] ?? null,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVATE USER PLAN HERE
                    |--------------------------------------------------------------------------
                    */

                    $plan = Plan::findOrFail($transaction->plan_id);

                    $subscriptionParams = [
                        'user_id' => $transaction->user_id,
                        'plan_id' => $transaction->plan_id,
                        'starts_at' => Carbon::now(),
                        'ends_at' => $plan->category == 'monthly' ? Carbon::now()->addDays(30) : Carbon::now()->addYears(1)
                    ];

                    $subscription = new Subscription($subscriptionParams);
                    $subscription->save();

                    $paymentParams = [
                        'user_id' => $transaction->user_id,
                        'amount' => $plan->price,
                        'date_paid' => Carbon::now()
                    ];

                    $payment = new \App\Models\Payment($paymentParams);
                    $payment->resource_id = $subscription->id;
                    $payment->resource_type = Subscription::class;
                    $payment->description = $plan->description;
                    $payment->save();

                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT FAILED
        |--------------------------------------------------------------------------
        */

        if ($eventType === 'transaction.payment_failed')
        {
            $transactionId = $data['id'] ?? null;

            PaddleTransaction::where(
                'paddle_transaction_id',
                $transactionId
            )->update([
                'status' => 'failed',
                'payload' => $payload
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }
}