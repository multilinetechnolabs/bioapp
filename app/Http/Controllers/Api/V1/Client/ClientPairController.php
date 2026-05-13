<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Api\V1\BaseController;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\ClientPair;
use App\Models\Client;

class ClientPairController extends BaseController
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
            $client_pairs = $client->clientPairs()->get();

            return response()->json($client_pairs, Response::HTTP_OK);
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
            $client_pair =  ClientPair::create($request->all());
            $client_pair->client_id = $client_id;

            return response()->json($client_pair, Response::HTTP_CREATED);
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
            $client_pair = $client->clientPairs()->findOrFail($id);

            return response()->json($client_pair, Response::HTTP_OK);
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
            $client_pair = $client->clientPairs()->findOrFail($id);
            $client_pair->update($request->all());

            return response()->json($client_pair, Response::HTTP_OK);
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
            $pair_id = $id;
            $client_pair = ClientPair::where('client_id', $client_id)->where('pair_id', $pair_id)->first();
            $client_pair->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
