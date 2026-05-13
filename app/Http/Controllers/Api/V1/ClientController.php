<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;
use DB;

use App\Models\Client;
use App\Support\DateInputFormatter;
use Carbon\Carbon;

class ClientController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $clients = $this->authorizedClientsQuery($request);
        $scanType = $request->input('scan_type');

        if (!empty($scanType)) {
            $clients->whereExists(function ($query) use ($scanType) {
                $query->select(DB::raw(1))
                    ->from('client_pairs')
                    ->join('pairs', 'client_pairs.pair_id', '=', 'pairs.id')
                    ->whereColumn('client_pairs.client_id', 'clients.id')
                    ->where('pairs.scan_type', '=', $scanType);
            });
        }

        $records = $clients
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($client) {
                return $this->serializeClientRecord($client);
            })
            ->values();

        return response()->json($records, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $condition = Auth::user()->isAdmin() || Auth::user()->isPractitioner() || Auth::user()->isTherapist();

        if ($condition) {
            $params = $this->normalizedParams($request);
            $params['user_id'] = Auth::user()->id;

            $client = new Client($params);

            if ($client->save()) {
                return response()->json($this->findClientRecordOrFail($client->id), Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($client->getErrors());
            }
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
        $client = $this->authorizedClientsQuery(request())
            ->where('id', '=', $id)
            ->first();

        if (!empty($client)) {
            return response()->json($this->serializeClientRecord($client), Response::HTTP_OK);
        }

        if (DB::table('clients')->where('id', '=', $id)->exists()) {
            return $this->sendUnauthorizedResponse();
        }

        abort(404);
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
        $client = Client::findOrFail($id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            if ($client->update($this->normalizedParams($request))) {
                return response()->json($this->findClientRecordOrFail($client->id), Response::HTTP_OK);
            }

            return $this->sendInvalidResponse($client->getErrors());
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
        $client = Client::findOrFail($id);

        if ($client->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $client->delete();

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
        $clients = $this->authorizedClientsQuery($request)->select([
            'id',
            'first_name',
            'last_name',
            'email',
            'address',
            'phone_no',
            'date_of_birth',
            'emergency_contact_person',
            'emergency_contact_number',
            'session_cost_type',
            'session_cost',
        ]);

        return DataTables::queryBuilder($clients)
            ->addColumn('age', function ($client) {
                return Carbon::parse($client->date_of_birth)->age;
            })
            ->addColumn('emergencyDetails', function ($client) {
                return "{$client->emergency_contact_person} ({$client->emergency_contact_number})";
            })
            ->addColumn('sessionDetails', function ($client) {
                $sessionType = ucfirst($client->session_cost_type);

                return "{$sessionType} (\${$client->session_cost})";
            })
            ->toJson();
    }

    protected function normalizedParams(Request $request)
    {
        $params = $request->all();

        if (array_key_exists('date_of_birth', $params)) {
            $params['date_of_birth'] = DateInputFormatter::toDatabaseDate($params['date_of_birth']);
        }

        return $params;
    }

    protected function authorizedClientsQuery(Request $request)
    {
        $actor = Auth::user();
        $userId = $request->input('user_id');
        $query = DB::table('clients')->select($this->clientSelectColumns());

        if ($actor->isAdmin()) {
            if (isset($userId)) {
                if ((int) $userId != (int) $actor->id) {
                    $query->where('user_id', '=', $userId);
                } else {
                    $query->where('user_id', '=', $actor->id);
                }
            }
        } else {
            $query->where('user_id', '=', $actor->id);
        }

        return $query;
    }

    protected function clientSelectColumns()
    {
        return [
            'id',
            'user_id',
            'first_name',
            'last_name',
            'email',
            'address',
            'phone_no',
            'date_of_birth',
            'emergency_contact_person',
            'emergency_contact_number',
            'session_cost_type',
            'session_cost',
            'session_paid',
            'gender',
            'created_at',
            'updated_at',
        ];
    }

    protected function findClientRecordOrFail($id)
    {
        $client = DB::table('clients')
            ->select($this->clientSelectColumns())
            ->where('id', '=', $id)
            ->first();

        if (empty($client)) {
            abort(404);
        }

        return $this->serializeClientRecord($client);
    }

    protected function serializeClientRecord($client)
    {
        $actor = Auth::user();
        $canManage = !empty($actor) && ($actor->isAdmin() || (int) $client->user_id === (int) $actor->id);

        $client->name = trim("{$client->first_name} {$client->last_name}");
        $client->age = Carbon::parse($client->date_of_birth)->age;
        $client->emergencyDetails = "{$client->emergency_contact_person} ({$client->emergency_contact_number})";
        $client->sessionDetails = ucfirst($client->session_cost_type)." (\${$client->session_cost})";
        $client->editable = $canManage;
        $client->deletable = $canManage;

        return $client;
    }
}
