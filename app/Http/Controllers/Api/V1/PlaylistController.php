<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\Playlist;

class PlaylistController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\JsonResponse
      */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', Playlist::class);

        if ($condition) {
            $playlists = Playlist::all();

            return response()->json($playlists, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Playlist::class);

        if ($condition) {
            $params = $request->all();
            if (empty($request->user_id)) {
                $params['user_id'] = Auth::user()->id;
            }

            $playlist = new Playlist($params);

            if ($playlist->save()) {
                return response()->json($playlist, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($playlist->getErrors());
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
        $playlist = Playlist::findOrFail($id);

        $condition = Auth::user()->can('read', $playlist);

        if ($condition) {
            return response()->json($playlist, Response::HTTP_OK);
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
        $playlist = Playlist::findOrFail($id);

        $condition = Auth::user()->can('edit', $playlist);

        if ($condition) {
            $params = $request->all();
            if (empty($request->user_id)) {
                $params['user_id'] = Auth::user()->id;
            }

            if ($playlist->update($params)) {
                return response()->json($playlist, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($playlist->getErrors());
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
        $playlist = Playlist::findOrFail($id);

        $condition = Auth::user()->can('edit', $playlist);

        if ($condition) {
            $playlist->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
