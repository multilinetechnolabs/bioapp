<?php

namespace App\Http\Controllers\Api\V1\Playlist;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\BaseController;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\Media;
use App\Models\MediaPlaylist;
use App\Models\Playlist;

class MediaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($playlist_id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $medias = $playlist->medias()->get()->each->append('file_url');

            return response()->json($medias, Response::HTTP_OK);
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
    public function show($playlist_id, $id)
    {
        $playlist = Playlist::findOrFail($playlist_id);

        if ($playlist->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $media = $playlist->medias()->findOrFail($id);
            $media->append('file_url');

            return response()->json($media, Response::HTTP_OK);
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
            $media_id = $id;
            $media_playlist = MediaPlaylist::where('media_id', $media_id)->where('media_id', $media_id)->first();
            $media_playlist->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
