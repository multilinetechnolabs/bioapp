<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Plan;
use App\Models\User;

class PlanController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($user_id = null)
    {
        $user = Auth::user();
        
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $condition = Auth::user()->can('browse', Plan::class);

        if ($condition || $user->id == Auth::user()->id) {
            $plans = $user->plans()->get();

            return response()->json($plans, Response::HTTP_OK);
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

        $plan = $user->plans()->findOrFail($id);

        $condition = Auth::user()->can('read', $plan);

        if ($condition) {
            return response()->json($plan, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
