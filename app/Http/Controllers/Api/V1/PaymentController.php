<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Symfony\Component\HttpFoundation\Response;

use PayPal\Api\Payment as PaypalPayment;
use PayPal\Api\PaymentExecution;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use Auth;
use Config;
use Session;
use URL;

use App\Models\Payment;

class PaymentController extends BaseController
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

    /**
      * Display a listing of the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\JsonResponse
      */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', Payment::class);

        if ($condition) {
            $payments = Payment::all();

            return response()->json($payments, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $condition = Auth::user()->can('add', Payment::class);

        if ($condition) {
            $params = $request->all();
    
            $payment = new Payment($params);

            if ($payment->save()) {
                return response()->json($payment, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($payment->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);

        $condition = Auth::user()->can('read', $payment);

        if ($condition) {
            return response()->json($payment, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $condition = Auth::user()->can('edit', $payment);

        if ($condition) {
            $params = $request->all();
    
            if ($payment->update($params)) {
                return response()->json($payment, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($payment->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        
        $condition = Auth::user()->can('delete', $payment);

        if ($condition) {
            $payment->delete();
    
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $paymentId = Session::get('paymentId');
        Session::forget('paymentId');

        $payerId = Input::get('PayerID');
        $token = Input::get('token');

        if (empty($paymentId)) {
            return $this->sendInvalidResponse([], 'Payment Failed. Missing value for `paymentId`', URL::route('affiliate.payment.index'));
        }

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            return $this->sendInvalidResponse([], 'Payment Failed. Missing value for `PayerID` or `token`', URL::route('affiliate.payment.index'));
        }

        $payment = PaypalPayment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() != 'approved') {
            return $this->sendInvalidResponse([], 'Payment Failed. An error occurred.');
        }

        $data = [
            'redirect_url' => URL::route('affiliate.payment.index')
        ];

        return $this->sendValidResponse($data, 'Payment Successful');
    }
}
