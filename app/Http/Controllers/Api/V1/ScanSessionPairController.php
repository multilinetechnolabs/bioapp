<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\ScanSessionPair;

class ScanSessionPairController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $scan_type = $request->input('scan_type');
        $name = $request->input('name');

        $scan_session_pairs = ScanSessionPair::query()
            ->select('scan_session_pairs.*')
            ->join('pairs', 'scan_session_pairs.pair_id', '=', 'pairs.id')
            ->join('scan_sessions', 'scan_session_pairs.scan_session_id', '=', 'scan_sessions.id')
            ->where('scan_sessions.user_id', '=', Auth::user()->id)
            ->with([
                'pair:id,name,radical,origins,symptoms,paths,alternative_routes,scan_type',
                'scanSession:id,client_id,user_id,scan_type',
                'scanSession.client:id,first_name,last_name',
            ]);

        if ($scan_type != '') {
            $scan_session_pairs = $scan_session_pairs->where('pairs.scan_type', '=', $scan_type);
        }

        if ($name != '') {
            $scan_session_pairs = $scan_session_pairs->where('pairs.name', 'like', '%'.$name.'%');
        }

        $scan_session_pairs = $scan_session_pairs
            ->orderBy('pairs.name', 'asc')
            ->get()
            ->map(function ($scan_session_pair) {
                $pair = $scan_session_pair->pair;
                $scan_session = $scan_session_pair->scanSession;
                $client = optional($scan_session)->client;

                return [
                    'id' => $scan_session_pair->id,
                    'scan_session_id' => $scan_session_pair->scan_session_id,
                    'pair_id' => $scan_session_pair->pair_id,
                    'scan_type' => optional($pair)->scan_type,
                    'pair' => [
                        'id' => optional($pair)->id,
                        'name' => optional($pair)->name,
                        'radical' => optional($pair)->radical,
                        'origins' => optional($pair)->origins,
                        'symptoms' => optional($pair)->symptoms,
                        'paths' => optional($pair)->paths,
                        'alternative_routes' => optional($pair)->alternative_routes,
                        'scan_type' => optional($pair)->scan_type,
                    ],
                    'scanSession' => [
                        'id' => optional($scan_session)->id,
                        'client' => [
                            'id' => optional($client)->id,
                            'name' => optional($client)->name,
                        ],
                    ],
                ];
            })
            ->values();

        return response()->json($scan_session_pairs, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $scan_session_pair = ScanSessionPair::findOrFail($id);

        return response()->json($scan_session_pair, Response::HTTP_OK);
    }
}
