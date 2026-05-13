<?php

namespace App\Http\Controllers\Api\V1\Client;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\ConsentForm;
use App\Models\Client;

class ConsentFormController extends BaseController
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
            $consent_forms = $client->consentForms()->get();

            return response()->json($consent_forms, Response::HTTP_OK);
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
            $consent_form = ConsentForm::create($request->all());

            return response()->json($consent_form, Response::HTTP_CREATED);
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
            $consent_form = $client->consentForms()->findOrFail($id);

            return response()->json($consent_form, Response::HTTP_OK);
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
            $consent_form = $client->consentForms()->findOrFail($id);
            $consent_form->update($request->all());

            return response()->json($consent_form, Response::HTTP_OK);
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
            $consent_form = $client->consentForms()->findOrFail($id);
            $consent_form->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
