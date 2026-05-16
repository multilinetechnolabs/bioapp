<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Auth;
use Config;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\PaddleTransaction;

use Paddle\SDK\Client;
use Paddle\SDK\Environment;
use Paddle\SDK\Options;

use Paddle\SDK\Entities\Shared\CustomData;
use Paddle\SDK\Resources\Transactions\Operations\CreateTransaction;

class PlanController extends Controller
{
    public function subscribe(Request $request)
    {
        if (Auth::user()->hasValidSubscription()) {
            return response()->json([
                'success' => false,
                'transaction_id' => "",
                'message' => "You still have a valid subscription. Action Forbidden."
            ]);
        }

        $plan = Plan::findOrFail($request->plan_id);

        if($plan->category == 'monthly'){
            $price_id = config('paddle.monthly_price_id');
        }elseif($plan->category == 'yearly'){
            $price_id = config('paddle.yearly_price_id');
        }else{
            return response()->json([
                'success' => false,
                'transaction_id' => "",
                'message' => "Invalid Subscription Plan"
            ]);
        }

        $paddleTransaction = PaddleTransaction::create([
            'user_id' => auth()->id(),
            'plan_id' => $request->plan_id,
            'paddle_price_id' => $price_id,
            'amount' => $plan->price,
            'currency' => 'USD',
            'status' => 'pending',
            'customer_email' => auth()->user()->email,
        ]);

        $environment = config('paddle.environment') === 'production'
            ? Environment::PRODUCTION
            : Environment::SANDBOX;

        $paddle = new Client(
            apiKey: config('paddle.api_key'),
            options: new Options($environment)
        );

        try {
            $transaction = $paddle->transactions->create(
                new CreateTransaction(
                    items: [
                        [
                            'price_id' => $price_id,
                            'quantity' => 1
                        ]
                    ]
                )
            );
            $paddleTransaction->update([
                'paddle_transaction_id' => $transaction->id
            ]);
        } catch (PayPalConnectionException $ex) {
            if (Config::get('app.debug')) {
                return response()->json([
                    'success' => false,
                    'transaction_id' => "",
                    'message' => "Payment Connection Timeout."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'transaction_id' => "",
                    'message' => "Payment cannot proceed. An unknown error occurred, please try again."
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id
        ]);
    }
}
