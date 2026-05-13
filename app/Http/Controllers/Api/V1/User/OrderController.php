<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Order;
use App\Models\User;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($user_id = null)
    {
        $condition = Auth::user()->can('browse', Order::class);

        $user = Auth::user();
        
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($condition || $user->id == Auth::user()->id) {
            $orders = $user->orders()->get();

            return response()->json($orders, Response::HTTP_OK);
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

        $order = $user->orders()->findOrFail($id);

        $condition = Auth::user()->can('read', $order);

        if ($condition) {
            return response()->json($order, Response::HTTP_OK);
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
        $condition = Auth::user()->can('datatables', Order::class);

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        if ($condition || $user->id == Auth::user()->id) {
            return DataTables::eloquent($user->orders())->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
