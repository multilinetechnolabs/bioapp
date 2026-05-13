<?php

namespace App\Http\Controllers\Api\V1\ScanSession;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Pair;
use App\Models\ScanSession;
use App\Models\ScanSessionPair;

class PairController extends BaseController
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
            $pairs = $scan_session->pairs()->get();

            return response()->json($pairs, Response::HTTP_OK);
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
            $pairs = $scan_session->pairs();
            $pair = $pairs->findOrFail($id);
    
            return response()->json($pair, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
