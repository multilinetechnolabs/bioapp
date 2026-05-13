<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\Product;

class ProductController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\JsonResponse
      */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', Product::class);

        if ($condition) {
            $products = Product::all();

            return response()->json($products, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Product::class);

        if ($condition) {
            $params = $request->all();
            if (empty($request->user_id)) {
                $params['user_id'] = Auth::user()->id;
            }

            $product = new Product($params);

            if ($product->save()) {
                return response()->json($product, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($product->getErrors());
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
        $product = Product::findOrFail($id);

        $condition = Auth::user()->can('read', $product);

        if ($condition) {
            return response()->json($product, Response::HTTP_OK);
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
        $product = Product::findOrFail($id);

        $condition = Auth::user()->can('edit', $product);

        if ($condition) {
            $params = $request->all();

            if ($product->update($params)) {
                return response()->json($product, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($product->getErrors());
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
        $product = Product::findOrFail($id);

        $condition = Auth::user()->can('edit', $product);

        if ($condition) {
            $product->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables(Request $request)
    {
        $condition = Auth::user()->can('datatables', Product::class);

        if ($condition) {
            $products = Product::query();

            return DataTables::eloquent($products)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
