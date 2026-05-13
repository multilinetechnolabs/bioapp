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

    public function subscribe(Request $request, $id)
    {
        if (Auth::user()->hasValidSubscription()) {
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'You still have a valid subscription. Action Forbidden.');
        }

        $plan = Plan::findOrFail($id);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $itemList = new ItemList();

        $item->setName($plan->description)->setCurrency('USD')->setQuantity('1')->setPrice($plan->price);
        $itemList->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($plan->price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($plan->description);

        $redirectTo = URL::route('app.plans.subscribe.status', ['id' => $plan->id]);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($redirectTo)->setCancelUrl($redirectTo);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContext);
        } catch (PayPalConnectionException $ex) {
            if (Config::get('app.debug')) {
                return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment Connection Timeout.');
            } else {
                return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment cannot proceed. An unknown error occurred, please try again.');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
                break;
            }
        }

        Session::put('paymentId', $payment->getId());

        if (isset($redirectUrl)) {
            return Redirect::away($redirectUrl);
        }

        return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment cannot proceed. An unknown error occurred, please try again.');
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
