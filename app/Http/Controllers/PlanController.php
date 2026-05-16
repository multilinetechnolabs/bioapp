<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

use Auth;
use Config;
use Redirect;
use Session;
use URL;

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
    private $apiContext;
    private $paypalConfig;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $paypalConfig = Config::get('paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalConfig['client_id'],
                $paypalConfig['secret']
        )
        );
        $this->apiContext->setConfig($paypalConfig['settings']);
    }

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
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Invalid Subscription Plan');
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

    public function status(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $paymentId = Session::get('paymentId');
        Session::forget('paymentId');

        $payerId = $request['PayerID'];
        $token = $request['token'];

        if (empty($payerId) || empty($token)) {
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment Failed. An unexpected error occurred, please try again.');
        }

        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() == 'approved') {
            $subscriptionParams = [
                'user_id' => Auth::user()->id,
                'plan_id' => $plan->id,
                'starts_at' => Carbon::now(),
                'ends_at' => $plan->category == 'monthly' ? Carbon::now()->addDays(30) : Carbon::now()->addYears(1)
            ];

            $subscription = new Subscription($subscriptionParams);
            $subscription->save();

            $paymentParams = [
                'user_id' => Auth::user()->id,
                'amount' => $plan->price,
                'date_paid' => Carbon::now()
            ];

            $payment = new \App\Models\Payment($paymentParams);
            $payment->resource_id = $subscription->id;
            $payment->resource_type = Subscription::class;
            $payment->description = $plan->description;
            $payment->save();

            return redirect()->to(URL::route('app.dashboard'))->with('message.success', 'Payment Successful. You are now entitled to use our services. Thank you.');
        }

        return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment Failed. An unexpected error occurred, please try again.');
    }
}
