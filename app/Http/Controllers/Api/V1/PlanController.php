<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\Plan;

class PlanController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', Plan::class);

        if ($condition) {
            $plans = Plan::all();

            return response()->json($plans, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Plan::class);

        if ($condition) {
            $params = $request->all();

            $plan = new Plan($params);

            if ($plan->save()) {
                return response()->json($plan, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($plan->getErrors());
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
        $plan = Plan::findOrFail($id);

        $condition = Auth::user()->can('read', $plan);

        if ($condition) {
            return response()->json($plan, Response::HTTP_OK);
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
        $plan = Plan::findOrFail($id);

        $condition = Auth::user()->can('edit', $plan);

        if ($condition) {
            $params = $request->all();

            if ($plan->update($params)) {
                return response()->json($plan, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($plan->getErrors());
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
        $plan = Plan::findOrFail($id);

        $condition = Auth::user()->can('delete', $plan);

        if ($condition) {
            $plan->delete();

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
        $condition = Auth::user()->can('datatables', Plan::class);

        if ($condition) {
            $plans = Plan::query();

            return DataTables::eloquent($plans)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
