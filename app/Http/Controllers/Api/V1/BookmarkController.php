<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\Bookmark;

class BookmarkController extends BaseController
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      */
    public function index()
    {
        $bookmarks = Bookmark::all();

        return response()->json($bookmarks, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $bookmark =  Bookmark::create($request->all());

        return response()->json($bookmark, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $bookmark = Bookmark::findOrFail($id);

        if ($bookmark->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        return response()->json($bookmark, Response::HTTP_OK);
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
        $bookmark = Bookmark::findOrFail($id);

        if ($bookmark->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        $bookmark->update($request->all());

        return response()->json($bookmark, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::findOrFail($id);

        if ($bookmark->user_id != Auth::user()->id) {
            return $this->sendUnauthorizedResponse();
        }

        $bookmark->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
