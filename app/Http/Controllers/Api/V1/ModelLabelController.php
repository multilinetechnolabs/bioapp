<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;

use App\Models\ModelLabel;

class ModelLabelController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', ModelLabel::class);

        if ($condition) {
            $target = $request->input('target') ?? 'female';
            $scan_type = $request->input('scan_type') ?? 'body_scan';

            if ($target != 'female') {
                $target = 'male';
            }
            if ($scan_type != 'body_scan') {
                $scan_type = 'chakra_scan';
            }
            
            if ($request->input('target') != '' || $request->input('scan_type') != '') {
                $modelLabels = ModelLabel::where('target', '=', $target)->where('scan_type', '=', $scan_type)->get();
            } else {
                $modelLabels = ModelLabel::all();
            }

            return response()->json($modelLabels, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', ModelLabel::class);

        if ($condition) {
            $params = $request->validate(ModelLabel::rules());

            $modelLabel = ModelLabel::create($params);
    
            return response()->json($modelLabel, Response::HTTP_CREATED);
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
        $modelLabel = ModelLabel::findOrFail($id);

        $condition = Auth::user()->can('read', $modelLabel);
        
        if ($condition) {
            return response()->json($modelLabel, Response::HTTP_OK);
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
        $modelLabel = ModelLabel::findOrFail($id);

        $condition = Auth::user()->can('edit', $modelLabel);

        if ($condition) {
            $params = $request->validate(ModelLabel::rules());

            $modelLabel->update($params);
    
            return response()->json($modelLabel, Response::HTTP_OK);
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
        $modelLabel = ModelLabel::findOrFail($id);

        $condition = Auth::user()->can('delete', $modelLabel);

        if ($condition) {
            $modelLabel->delete();

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
        $condition = Auth::user()->can('datatables', ModelLabel::class);

        if ($condition) {
            $scan_type = $request->input('scan_type');

            if ($scan_type != '') {
                $modelLabels = ModelLabel::with('point')->where('scan_type', '=', $scan_type);
            } else {
                $modelLabels   = ModelLabel::with('point');
            }
        
            return DataTables::of($modelLabels)->toJson();
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
