<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\Pair;

class PairController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', Pair::class);

        if ($condition) {
            $scan_type = $request->input('scan_type');

            if ($scan_type != '') {
                $pairs = Pair::orderBy('name', 'asc')->where('scan_type', '=', $scan_type)->get();
            } else {
                $pairs = Pair::orderBy('name', 'asc')->get();
            }
    
            return response()->json($pairs, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Pair::class);

        if ($condition) {
            $params = $request->all();

            $pair = new Pair($params);

            if ($pair->save()) {
                return response()->json($pair, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($pair->getErrors());
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
        $pair = Pair::findOrFail($id);

        $condition = Auth::user()->can('read', $pair);

        if ($condition) {
            return response()->json($pair, Response::HTTP_OK);
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
        $pair = Pair::findOrFail($id);

        $condition = Auth::user()->can('edit', $pair);

        if ($condition) {
            $params = $request->all();

            if ($pair->update($params)) {
                return response()->json($pair, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($pair->getErrors());
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
        $pair = Pair::findOrFail($id);

        $condition = Auth::user()->can('delete', $pair);

        if ($condition) {
            $pair->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }

        if (Auth::user() && !Auth::user()->isAdmin()) {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables(Request $request)
    {
        $condition = Auth::user()->can('datatables', Pair::class);

        if ($condition) {
            $pairs = Pair::orderBy('ref_no')->get();

            if (!empty($request->scan_type)) {
                $pairs = Pair::orderBy('ref_no')->where('scan_type', $request->scan_type)->get();
            }

            return DataTables::of($pairs)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
