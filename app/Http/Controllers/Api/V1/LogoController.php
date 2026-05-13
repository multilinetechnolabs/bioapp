<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;
use Storage;

use App\Models\Logo;

class LogoController extends BaseController
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

        $condition = Auth::user()->can('browse', Logo::class);

        if ($condition) {
            if (Auth::user()->isAdmin()) {
                if (isset($user_id)) {
                    if ($user_id != Auth::user()->id) {
                        $logos = Logo::where('user_id', '=', $user_id)->get();
                    } else {
                        $logos = Logo::where('user_id', '=', Auth::user()->id)->get();
                    }
                } else {
                    $logos = Logo::all();
                }
            } else {
                $logos = Logo::where('user_id', '=', Auth::user()->id)->get();
            }

            return response()->json($logos, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Logo::class);

        if ($condition) {
            $params = $request->all();
            $params['user_id'] = Auth::user()->id;

            $logo = new Logo($params);

            if ($logo->save()) {
                return response()->json($logo, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($logo->getErrors());
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
        $logo = Logo::findOrFail($id);

        $condition = Auth::user()->can('read', $logo);

        if ($condition) {
            return response()->json($logo, Response::HTTP_OK);
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
        $logo = Logo::findOrFail($id);

        $condition = Auth::user()->can('edit', $logo);

        if ($condition) {
            $params = $request->all();
            
            if ($logo->update($params)) {
                return response()->json($logo, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($logo->getErrors());
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
        $logo = Logo::findOrFail($id);

        $condition = Auth::user()->can('delete', $logo);

        if ($condition) {
            $logo->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
