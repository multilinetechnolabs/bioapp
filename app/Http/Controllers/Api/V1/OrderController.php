<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\Order;

class OrderController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', Order::class);

        if ($condition) {
            $orders = Order::all();

            return response()->json($orders, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Order::class);

        if ($condition) {
            $params = $request->all();
            if (empty($request->user_id)) {
                $params['user_id'] = Auth::user()->id;
            }
    
            $order = new Order($params);

            if ($order->save()) {
                return response()->json($order, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($order->getErrors());
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
        $order = Order::findOrFail($id);

        $condition = Auth::user()->can('read', $order);

        if ($condition) {
            return response()->json($order, Response::HTTP_OK);
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
        $order = Order::findOrFail($id);

        $condition = Auth::user()->can('edit', $order);

        if ($condition) {
            $params = $request->all();

            if ($order->update($params)) {
                return response()->json($order, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($order->getErrors());
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
        $order = Order::findOrFail($id);

        $condition = Auth::user()->can('delete', $order);

        if ($condition) {
            $order->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        $condition = Auth::user()->can('datatables', Order::class);

        if ($condition) {
            $orders = Order::query();

            return DataTables::eloquent($orders)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
