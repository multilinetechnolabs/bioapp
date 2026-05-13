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

use App\Models\Order;

class OrderController extends Controller
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

    public function payment(Request $request, $id)
    {
        $order = Order::find($id);
        $price = $order->product->unit_price;
        $quantity = strval($order->quantity);
        
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $itemList = new ItemList();

        // Set items list
        $item->setName($order->description)->setCurrency('USD')->setQuantity($quantity)->setPrice($price);

        if ($order->shipping_rate > 0.0) {
            $shippingFee = new Item();
            $shippingFee->setName('Shipping fee')->setCurrency('USD')->setQuantity('1')->setPrice($order->shipping_rate);
        }

        if (!empty($shippingFee)) {
            $itemList->setItems(array($item, $shippingFee));
        } else {
            $itemList->setItems(array($item));
        }

        // Calculate total amount
        $totalAmount = $price * $quantity;
        if ($order->shipping_rate > 0.0) {
            $totalAmount += $order->shipping_rate;
        }
        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal(floatval($totalAmount));

        // Set transaction decription
        $description = $quantity.' pcs. - '.$order->description;
        if ($order->shipping_rate > 0.0) {
            $description = $description.' with Shipping Fee';
        }

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description);

        $redirectTo = URL::route('app.orders.payment.status', ['id' => $order->id]);
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

    public function payment_status(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $price = $order->product->unit_price;
        $quantity = strval($order->quantity);

        $description = $order->description;
        if ($order->shipping_rate > 0.0) {
            $description = $description.' with Shipping Fee';
        }

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
            $paymentParams = [
                'user_id' => $order->user_id,
                'amount' => $price * $quantity,
                'date_paid' => Carbon::now()
            ];

            $payment = new \App\Models\Payment($paymentParams);
            $payment->resource_id = $order->id;
            $payment->resource_type = Order::class;
            $payment->description = $quantity.' pcs. - '.$description;
            $payment->save();

            return redirect()->to(URL::route('app.dashboard'))->with('message.success', 'Payment Successful. Thank you for purchasing our order/s.');
        }

        return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment Failed. An unexpected error occurred, please try again.');
    }
}
