<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\Media;

class MediaController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\JsonResponse
      */
    public function index(Request $request)
    {
        $condition = Auth::user()->can('browse', Media::class);

        if ($condition) {
            $medias = Media::all()->each->append('file_url');

            return response()->json($medias, Response::HTTP_OK);
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
        $condition = Auth::user()->can('add', Media::class);

        if ($condition) {
            $params = $request->all();
            if (empty($request->user_id)) {
                $params['user_id'] = Auth::user()->id;
            }

            $media = new Media($params);

            if ($media->save()) {
                $media->append('file_url');

                return response()->json($media, Response::HTTP_CREATED);
            } else {
                return $this->sendInvalidResponse($media->getErrors());
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
        $media = Media::findOrFail($id);

        $condition = Auth::user()->can('read', $media);

        if ($condition) {
            $media->append('file_url');

            return response()->json($media, Response::HTTP_OK);
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
        $media = Media::findOrFail($id);

        $condition = Auth::user()->can('edit', $media);

        if ($condition) {
            $params = $request->all();
            if (empty($request['user_id'])) {
                $params['user_id'] = Auth::user()->id;
            }

            if ($media->update($params)) {
                $media->append('file_url');

                return response()->json($media, Response::HTTP_OK);
            } else {
                return $this->sendInvalidResponse($media->getErrors());
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
        $media = Media::findOrFail($id);

        $condition = Auth::user()->can('delete', $media);

        if ($condition) {
            $media->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
