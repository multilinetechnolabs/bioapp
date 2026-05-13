<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\DrGoizPair;

class DrGoizPairController extends BaseController
{
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', DrGoizPair::class);

        if ($condition) {
            $pairs = DrGoizPair::orderBy('name')->get();
            return response()->json($pairs, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function store(Request $request)
    {
        $condition = Auth::user()->can('add', DrGoizPair::class);

        if ($condition) {
            $pair = new DrGoizPair($request->all());

            if ($pair->save()) {
                return response()->json($pair, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($pair->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function show($id)
    {
        $pair = DrGoizPair::findOrFail($id);

        $condition = Auth::user()->can('read', $pair);

        if ($condition) {
            return response()->json($pair, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function update(Request $request, $id)
    {
        $pair = DrGoizPair::findOrFail($id);

        $condition = Auth::user()->can('edit', $pair);

        if ($condition) {
            if ($pair->update($request->all())) {
                return response()->json($pair, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($pair->getErrors());
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function destroy($id)
    {
        $pair = DrGoizPair::findOrFail($id);

        $condition = Auth::user()->can('delete', $pair);

        if ($condition) {
            $pair->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    public function datatables(Request $request)
    {
        $condition = Auth::user()->can('datatables', DrGoizPair::class);

        if ($condition) {
            $pairs = DrGoizPair::orderBy('name')->get();
            return DataTables::of($pairs)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
