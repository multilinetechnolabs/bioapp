<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Payment;
use App\Models\User;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($user_id = null)
    {
        $condition = Auth::user()->can('browse', Payment::class);

        $user = Auth::user();
        
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($condition || $user->id == Auth::user()->id) {
            $payments = $user->payments()->get();

            return response()->json($payments, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user_id = $request->user_id;
        
        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $payment = $user->payments()->findOrFail($id);

        $condition = Auth::user()->can('read', $payment);

        if ($condition) {
            return response()->json($payment, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables($user_id = null)
    {
        $condition = Auth::user()->can('datatables', Payment::class);

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($condition || $user->id == Auth::user()->id) {
            return DataTables::eloquent($user->payments())->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
