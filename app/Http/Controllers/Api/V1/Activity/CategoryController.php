<?php

namespace App\Http\Controllers\Api\V1\Activity;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Activity\Category;

class CategoryController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $condition = Auth::user()->can('browse', Category::class);

        if ($condition) {
            $categories = Category::all();

            return response()->json($categories, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Category::class);

        if ($condition) {
            $params = $request->all();

            $category = new Category($params);
            
            if ($category->save()) {
                return response()->json($category, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($category->getErrors());
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
        $category = Category::findOrFail($id);

        $condition = Auth::user()->can('read', $category);

        if ($condition) {
            return response()->json($category, Response::HTTP_OK);
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
        $category = Category::findOrFail($id);

        $condition = Auth::user()->can('edit', $category);

        if ($condition) {
            $params = $request->all();

            if ($category->update($params)) {
                return response()->json($category, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($category->getErrors());
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
        $category = Category::findOrFail($id);

        $condition = Auth::user()->can('delete', $category);

        if ($condition) {
            $category->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
