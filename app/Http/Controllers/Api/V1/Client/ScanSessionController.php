<?php

namespace App\Http\Controllers\Api\V1\Client;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\ScanSession;
use App\Models\Client;

class ScanSessionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($client_id)
    {
        $client = Client::findOrFail($client_id);
        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_sessions = $client->scanSessions()->get();

            return response()->json($scan_sessions, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(Request $request, $client_id)
    {
        $client = Client::findOrFail($client_id);
        $scan_type = $request->scan_type ?? 'body_scan';

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session = $client->scanSessions()->orderBy('date_started', 'asc')->get()->where('scan_type', $scan_type)->where('date_ended', null)->first();

            if ($scan_session) {
                return response()->json($scan_session, Response::HTTP_OK);
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $client_id)
    {
        $client = Client::findOrFail($client_id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session =  new ScanSession;
            $scan_session->client_id = $client_id;
            $scan_session->user_id = Auth::user()->id;
            $scan_session->scan_type = $request->scan_type;
            $scan_session->date_started = $request->date_started;
            $scan_session->date_ended = $request->date_ended;
            $scan_session->cost = $request->cost;
    
            if (!empty($client)) {
                $scan_session->cost_type = $client->session_cost_type;
            }
    
            $scan_session->save();
    
            return response()->json($scan_session, Response::HTTP_CREATED);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $client_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($client_id, $id)
    {
        $client = Client::findOrFail($client_id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session = $client->scanSessions()->findOrFail($id);

            if ($scan_session) {
                return response()->json($scan_session, Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Record Not Found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $client_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $client_id, $id)
    {
        $client = Client::findOrFail($client_id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session = $client->scanSessions()->findOrFail($id);
            $scan_session->update($request->all());

            return response()->json($scan_session, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $client_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($client_id, $id)
    {
        $client = Client::findOrFail($client_id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session = $client->scanSessions()->findOrFail($id);
            $scan_session->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
