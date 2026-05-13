<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\Activity;

class ActivityController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', Activity::class);

        if ($condition) {
            $activities = Activity::all();

            return response()->json($activities, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Activity::class);

        if ($condition) {
            $params = $request->all();
            $params['user_id'] = Auth::user()->id;
    
            $activity = new Activity($params);

            if ($activity->save()) {
                return response()->json($activity, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($activity->getErrors());
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
        $activity = Activity::findOrFail($id);

        $condition = Auth::user()->can('read', $activity);

        if ($condition) {
            return response()->json($activity, Response::HTTP_OK);
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
        $activity = Activity::findOrFail($id);

        $condition = Auth::user()->can('edit', $activity);

        if ($condition) {
            $params = $request->all();

            if ($activity->update($params)) {
                return response()->json($activity, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($activity->getErrors());
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
        $activity = Activity::findOrFail($id);

        $condition = Auth::user()->can('delete', $activity);

        if ($condition) {
            $activity->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
