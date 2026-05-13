<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use DataTables;
use Storage;

use Aws\S3\Exception\S3Exception;

use App\Models\User;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $condition = Auth::user()->can('browse', User::class);

        if ($condition) {
            return response()->json(User::all(), Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', User::class);

        if ($condition) {
            $params = $request->all();
            
            $user = new User($params);

            if ($user->save()) {
                if (!empty($request->type)) {
                    if (!empty($user->type)) {
                        $user->removeRole($user->type);
                    }
                    $user->assignRole($request->type);
                }

                return response()->json($user, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($user->getErrors());
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
    public function show($id = null)
    {
        $user = Auth::user();

        if (!empty($id)) {
            $user = User::findOrFail($id);
        }

        $condition = Auth::user()->can('read', $user);
        
        if ($condition) {
            return response()->json($user, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id = null)
    {
        $user = Auth::user();

        if (!empty($id)) {
            $user = User::findOrFail($id);
        }

        $condition = Auth::user()->can('edit', $user);

        if ($condition) {
            $params = $request->all();

            if ($user->update($params)) {
                if (!empty($request->type)) {
                    if (!empty($user->type)) {
                        $user->removeRole($user->type);
                    }
                    $user->assignRole($request->type);
                }

                return response()->json($user, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($user->getErrors());
            }
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
        $user = User::findOrFail($id);

        $condition = Auth::user()->can('delete', $user);

        if ($condition) {
            $user->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /*
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatables()
    {
        if (Auth::user() && !Auth::user()->isAdmin()) {
            return $this->sendUnauthorizedResponse();
        }

        $users = User::query();

        return DataTables::eloquent($users)->toJson();
    }
}
