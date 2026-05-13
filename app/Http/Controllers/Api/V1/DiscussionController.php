<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Models\GroupDiscussion as Discussion;

class DiscussionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $discussions = Discussion::orderBy('created_at', 'desc')->get();

        return response()->json($discussions, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;
        $discussion =  Discussion::create($params);

        return response()->json($discussion, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $discussion = Discussion::findOrFail($id);

        return response()->json($discussion, Response::HTTP_OK);
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
        $discussion = Discussion::findOrFail($id);

        if (($discussion->created_by == Auth::user()->id) || Auth::user()->isAdmin()) {
            $params = $request->all();
            $params['created_by'] = $discussion->created_by;
            $discussion->update($params);

            return response()->json($discussion, Response::HTTP_OK);
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
        $discussion = Discussion::findOrFail($id);
        
        if (($discussion->created_by == Auth::user()->id) || Auth::user()->isAdmin()) {
            $discussion->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
