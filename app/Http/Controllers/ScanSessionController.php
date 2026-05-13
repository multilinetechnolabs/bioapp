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

use Mail;
use Illuminate\Support\Facades\Log;
use App\Exports\ScanSessionExport;
use App\Mail\ScanSessionPaymentEmail;

use App\Models\ScanSession;

class ScanSessionController extends Controller
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

        if (!empty($paypalConfig['client_id']) && !empty($paypalConfig['secret'])) {
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $paypalConfig['client_id'],
                    $paypalConfig['secret']
                )
            );
            $this->apiContext->setConfig($paypalConfig['settings']);
        }
    }

    public function export($id)
    {
        $scanSession = ScanSession::findOrFail($id);

        if ($scanSession->paid) {
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }

        if (!empty($scanSession) && ($scanSession->user_id == Auth::user()->id || Auth::user()->isAdmin())) {
            $filename = $scanSession->export();
            $file = storage_path().'/app/'. $filename;

            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
             ];
  
            return response()->download($file, $filename, $headers)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }
    }

    public function print($id)
    {
        $scanSession = ScanSession::findOrFail($id);

        if ($scanSession->paid) {
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }

        if (!empty($scanSession) && ($scanSession->user_id == Auth::user()->id || Auth::user()->isAdmin())) {
            return view('app.pages.scan_sessions.print', [
                'scanSession' => $scanSession,
            ]);
        }

        return response()->json(['error' => 'Unauthorized Access'], 401);
    }
    
    public function payment(Request $request, $id)
    {
        $scanSession = ScanSession::findOrFail($id);

        if (empty($this->apiContext)) {
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment is not configured right now.');
        }

        if ($scanSession->paid) {
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'You have already paid this scan session. Action Forbidden.');
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $itemList = new ItemList();

        $item->setName($scanSession->description())->setCurrency('USD')->setQuantity('1')->setPrice($scanSession->cost);
        $itemList->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency('USD')->setTotal($scanSession->cost);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($scanSession->description());

        $redirectTo = URL::route('app.scanSessions.payment.status', ['id' => $scanSession->id]);
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
        $scanSession = ScanSession::findOrFail($id);

        if (empty($this->apiContext)) {
            return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment is not configured right now.');
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
            $payment = new \App\Models\Payment(['amount' => $scanSession->cost, 'date_paid' => Carbon::now()]);
            $payment->resource_id = $scanSession->id;
            $payment->resource_type = ScanSession::class;
            $payment->description = $scanSession->description();
            $payment->save();

            return redirect()->to(URL::route('app.dashboard'))->with('message.success', 'Payment Successful. You have successfully paid your scan session. Thank you.');
        }

        return redirect()->to(URL::route('app.dashboard'))->with('message.fail', 'Payment Failed. An unexpected error occurred, please try again.');
    }

    public function requestPayment($id)
    {
        $scanSession = ScanSession::findOrFail($id);

        try {
            Mail::to($scanSession->client->email)->bcc(env('MAIL_FROM_ADDRESS_BCC'))->send(new ScanSessionPaymentEmail($scanSession));
        } catch (\Throwable $e) {
            Log::error('Unable to send scan session payment request email.', [
                'scan_session_id' => $scanSession->id,
                'client_id' => $scanSession->client_id,
                'client_email' => $scanSession->client->email,
                'exception' => $e,
            ]);

            return back()->with('message.fail', 'Unable to send the payment request email right now. Please try again.');
        }

        return back()->with('message.success', 'Payment Request Sent. You have successfully send a request to the client.');
    }
}
