<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\CoursePurchase;

class CourseSubscriptionController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', CoursePurchase::class);

        if ($condition) {
            $coursePurchases = CoursePurchase::all();

            return response()->json($coursePurchases, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', CoursePurchase::class);

        if ($condition) {
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coursePurchase = CoursePurchase::findOrFail($id);

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
        $coursePurchase = CoursePurchase::findOrFail($id);

        $condition = Auth::user()->can('edit', $coursePurchase);

        if ($condition) {
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $coursePurchase = CoursePurchase::findOrFail($id);

        $condition = Auth::user()->can('delete', $coursePurchase);

        if ($condition) {
            $coursePurchase = CoursePurchase::findOrFail($id);
            $coursePurchase->delete();

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
        $condition = Auth::user()->can('datatables', CoursePurchase::class);

        if ($condition) {
            $coursePurchases = CoursePurchase::query()->latest();

            return DataTables::eloquent($coursePurchases)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
