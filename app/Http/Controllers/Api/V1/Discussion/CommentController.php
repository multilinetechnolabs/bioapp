<?php

namespace App\Http\Controllers\Api\V1\Discussion;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Auth;

use App\Http\Controllers\Api\V1\BaseController;

use App\Models\GroupDiscussion as Discussion;
use App\Models\Discussion\Comment;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $discussion_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $discussion_id)
    {
        $discussion = Discussion::findOrFail($discussion_id);
        $comments = $discussion->comments()->get();

        return response()->json($comments, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $discussion_id)
    {
        $discussion = Discussion::findOrFail($discussion_id);
        $comment = Comment::create($request->all());

        return response()->json($comment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $discussion_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($discussion_id, $id)
    {
        $discussion = Discussion::findOrFail($discussion_id);
        $comment = $discussion->comments()->findOrFail($id);

        return response()->json($comment, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $discussion_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $discussion_id, $id)
    {
        $discussion = Discussion::findOrFail($discussion_id);
        $comment = $discussion->comments()->findOrFail($id);

        if ($comment->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $comment->update($request->all());

            return response()->json($comment, Response::HTTP_OK);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $discussion_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($discussion_id, $id)
    {
        $discussion = Discussion::findOrFail($discussion_id);
        $comment = $discussion->comments()->findOrFail($id);

        if ($comment->user_id == Auth::user()->id || Auth::user()->isAdmin()) {
            $comment->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return $this->sendUnauthorizedResponse();
        }
    }
}
