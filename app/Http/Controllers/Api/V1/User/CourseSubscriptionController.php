<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\CoursePurchase;
use App\Models\User;

class CourseSubscriptionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($user_id = null)
    {
        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $condition = Auth::user()->can('browse', CoursePurchase::class);

        if ($condition || $user->id == Auth::user()->id) {
            $coursePurchases = $user->courseSubscriptions()->get();

            return response()->json($coursePurchases, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $user_id = null)
    {
        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $condition = Auth::user()->can('add', CoursePurchase::class);

        if ($condition) {
            $request['user_id'] = $user->id;
            $params = $request->validate(CoursePurchase::rules());

            $coursePurchase = CoursePurchase::create($params);

            return response()->json($coursePurchase, Response::HTTP_CREATED);
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

        $coursePurchase = $user->courseSubscriptions()->findOrFail($id);

        $condition = Auth::user()->can('read', $coursePurchase);

        if ($condition) {
            return response()->json($coursePurchase, Response::HTTP_OK);
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
        $user_id = $request->user_id;

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $coursePurchase = $user->courseSubscriptions()->findOrFail($id);

        $condition = Auth::user()->can('edit', $coursePurchase);

        if ($condition) {
            if (empty($request['course_id'])) {
                $request['course_id'] = $coursePurchase->course_id;
            }
            $request['user_id'] = $user->id;

            $params = $request->validate(CoursePurchase::rules());

            $coursePurchase->update($params);

            return response()->json($coursePurchase, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user_id = $request->user_id;

        $user = Auth::user();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        }

        $coursePurchase = $user->courseSubscriptions()->findOrFail($id);

        $condition = Auth::user()->can('delete', $coursePurchase);

        if ($condition) {
            $coursePurchase->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
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
        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
        } else {
            $user = Auth::user();
        }

        $condition = Auth::user()->can('datatables', CoursePurchase::class);

        if ($condition || $user->id == Auth::user()->id) {
            return DataTables::eloquent($user->courseSubscriptions())->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
