<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\Subscription;

class SubscriptionController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', Subscription::class);

        if ($condition) {
            $subscriptions = Subscription::all();

            return response()->json($subscriptions, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Subscription::class);

        if ($condition) {
            $params = $request->all();

            $subscription = new Subscription($params);

            if ($subscription->save()) {
                return response()->json($subscription, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($subscription->getErrors());
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
        $subscription = Subscription::findOrFail($id);

        $condition = Auth::user()->can('read', $subscription);

        if ($condition) {
            return response()->json($subscription, Response::HTTP_OK);
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
        $subscription = Subscription::findOrFail($id);

        $condition = Auth::user()->can('edit', $subscription);

        if ($condition) {
            $params = $request->all();

            if ($subscription->update($params)) {
                return response()->json($subscription, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($subscription->getErrors());
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
        $subscription = Subscription::findOrFail($id);

        $condition = Auth::user()->can('delete', $subscription);

        if ($condition) {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();

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
        $condition = Auth::user()->can('datatables', Subscription::class);

        if ($condition) {
            $subscriptions = Subscription::query()->latest();

            return DataTables::eloquent($subscriptions)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
