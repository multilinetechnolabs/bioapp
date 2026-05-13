<?php

namespace App\Http\Controllers\Api\V1\Client;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Client;
use App\Models\Pair;
use App\Models\ClientPair;

class PairController extends BaseController
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
            $pairs = $client->pairs()->get();

            return response()->json($pairs, Response::HTTP_OK);
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
            $pair = $client->pairs()->findOrFail($id);

            return response()->json($pair, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
