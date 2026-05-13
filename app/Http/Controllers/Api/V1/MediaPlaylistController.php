<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\MediaPlaylist;

class MediaPlaylistController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $mediaPlaylists = MediaPlaylist::all();

        return response()->json($mediaPlaylists, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $mediaPlaylist =  MediaPlaylist::create($request->all());

        return response()->json($mediaPlaylist, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $mediaPlaylist = MediaPlaylist::findOrFail($id);

        if ($mediaPlaylist->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        return response()->json($mediaPlaylist, Response::HTTP_OK);
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
        $mediaPlaylist = MediaPlaylist::findOrFail($id);

        if ($mediaPlaylist->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        $mediaPlaylist->update($request->all());

        return response()->json($mediaPlaylist, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $mediaPlaylist = MediaPlaylist::findOrFail($id);

        if ($mediaPlaylist->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        $mediaPlaylist->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
