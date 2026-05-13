<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\ScanSessionMail;

use App\Models\Client;
use App\Models\ScanSession;

class ScanSessionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user_id = $request->input('user_id');

        if (Auth::user()->isAdmin()) {
            if (isset($user_id)) {
                if ($user_id != Auth::user()->id) {
                    $scan_sessions = ScanSession::where('user_id', '=', $user_id)->get();
                } else {
                    $scan_sessions = ScanSession::where('user_id', '=', Auth::user()->id)->get();
                }
            } else {
                $scan_sessions = ScanSession::all();
            }
        } else {
            $scan_sessions = ScanSession::where('user_id', '=', Auth::user()->id)->get();
        }

        if (!empty($request->scan_type)) {
            $scan_sessions = ScanSession::where('user_id', '=', Auth::user()->id)->where('scan_type', 'chakra_scan')->get();
        }

        return response()->json($scan_sessions, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $scan_session = new ScanSession;
        $scan_session->user_id = Auth::user()->id;
        $scan_session->client_id = $request->client_id;
        $scan_session->scan_type = $request->scan_type;
        $scan_session->date_started = $request->date_started;
        $scan_session->date_ended = $request->date_ended;

        $client = Client::findOrFail($request->client_id);
        if (!empty($client)) {
            $scan_session->cost_type = $client->session_cost_type;
            $scan_session->cost = $client->session_cost;
        }
       
        $scan_session->save();
        
        return response()->json($scan_session, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $scan_session = ScanSession::findOrFail($id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            return response()->json($scan_session, Response::HTTP_OK);
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
        $scan_session = ScanSession::findOrFail($id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session->update($request->all());

            return response()->json($scan_session, Response::HTTP_OK);
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
        $scan_session = ScanSession::findOrFail($id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $scan_session->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function active(Request $request)
    {
        $scan_type = $request->scan_type;

        if (!empty($scan_type)) {
            $scan_sessions = ScanSession::where('user_id', Auth::user()->id)->where('scan_type', $scan_type);
        } else {
            $scan_sessions = ScanSession::where('user_id', Auth::user()->id);
        }

        $scan_sessions = $scan_sessions->where('date_ended', null);
        $scan_session = $scan_sessions->first();
        
        if (empty($scan_session)) {
            return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json($scan_session, Response::HTTP_OK);
    }
    
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mail(Request $request, $id)
    {
        $scan_session = ScanSession::findOrFail($id);

        if ($scan_session->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $email_to = $scan_session->client->email;
            $email_bcc = env('MAIL_FROM_ADDRESS_BCC');

            try {
                Mail::to($email_to)->bcc($email_bcc)->send(new \App\Mail\ScanSessionEmail($scan_session));
            } catch (\Throwable $e) {
                Log::error('Unable to send scan session email.', [
                    'scan_session_id' => $scan_session->id,
                    'client_id' => $scan_session->client_id,
                    'client_email' => $email_to,
                    'exception' => $e,
                ]);

                return $this->sendErrorResponse('Unable to email the client right now. Please verify the mail configuration and try again.');
            }

            return response()->json(['message' => 'Message sent successfully'], Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
