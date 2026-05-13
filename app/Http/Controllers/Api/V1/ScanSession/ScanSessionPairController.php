<?php

namespace App\Http\Controllers\Api\V1\ScanSession;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\ScanSession;
use App\Models\ScanSessionPair;

class ScanSessionPairController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $scan_session_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($scan_session_id)
    {
        $scan_session = ScanSession::findOrFail($scan_session_id);
        
        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session_pairs = ScanSessionPair::where('scan_session_id', $scan_session_id)->get();

            return response()->json($scan_session_pairs, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $scan_session_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $scan_session_id)
    {
        $scan_session = ScanSession::findOrFail($scan_session_id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session_pair =  new ScanSessionPair;
            $scan_session_pair->scan_session_id = $scan_session_id;
            $scan_session_pair->user_id = Auth::user()->id;
            $scan_session_pair->pair_id = $request->pair_id;
            $scan_session_pair->save();

            return response()->json($scan_session_pair, Response::HTTP_CREATED);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $scan_session_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($scan_session_id, $id)
    {
        $scan_session = ScanSession::findOrFail($scan_session_id);
        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session_pair = ScanSessionPair::findOrFail($id);

            return response()->json($scan_session_pair, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $scan_session_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $scan_session_id, $id)
    {
        $scan_session = ScanSession::findOrFail($scan_session_id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session_pair = ScanSessionPair::findOrFail($id);
            $scan_session_pair->update($request->all());

            return response()->json($scan_session_pair, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $scan_session_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($scan_session_id, $id)
    {
        $scan_session = ScanSession::findOrFail($scan_session_id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $pair_id = $id;
            $scan_session_pair = ScanSessionPair::where('scan_session_id', $scan_session_id)->where('pair_id', $pair_id)->first();
            $scan_session_pair->delete();
    
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
