<?php

namespace App\Http\Controllers\Api\V1\Plan;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Plan;

class SubscriptionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $plan_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);

        if ($plan->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $subscriptions = $plan->subscriptions()->get();

            return response()->json($subscriptions, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $plan_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($plan_id, $id)
    {
        $plan = Plan::findOrFail($plan_id);

        if ($plan->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $subscription = $plan->subscriptions()->findOrFail($id);

            return response()->json($subscription, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
