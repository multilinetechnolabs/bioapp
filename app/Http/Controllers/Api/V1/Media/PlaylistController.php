<?php

namespace App\Http\Controllers\Api\V1\Media;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\Playlist;
use App\Models\Media;

class PlaylistController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $media_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($media_id)
    {
        $media = Media::findOrFail($media_id);

        if ($media->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $playlists = $media->playlists()->get();

            return response()->json($playlists, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $media_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($media_id, $id)
    {
        $media = Media::findOrFail($media_id);

        if ($media->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $playlist = $media->playlists()->findOrFail($id);

            return response()->json($playlist, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
