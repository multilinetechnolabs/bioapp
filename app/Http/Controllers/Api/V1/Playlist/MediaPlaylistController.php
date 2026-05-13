<?php

namespace App\Http\Controllers\Api\V1\Playlist;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\MediaPlaylist;
use App\Models\Playlist;

class MediaPlaylistController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $playlist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($playlist_id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $mediaPlaylists = $playlist->mediaPlaylists()->get();

            return response()->json($mediaPlaylists, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $playlist_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $playlist_id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $mediaPlaylist =  MediaPlaylist::create($request->all());
            $mediaPlaylist->playlist_id = $playlist_id;

            return response()->json($mediaPlaylist, Response::HTTP_CREATED);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $playlist_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($playlist_id, $id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $mediaPlaylist = $playlist->mediaPlaylists()->findOrFail($id);

            return response()->json($mediaPlaylist, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $playlist_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $playlist_id, $id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $mediaPlaylist = $playlist->mediaPlaylists()->findOrFail($id);
            $mediaPlaylist->update($request->all());

            return response()->json($mediaPlaylist, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $playlist_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($playlist_id, $id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $playlist = $playlist->mediaPlaylists()->findOrFail($id);
            $playlist->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
