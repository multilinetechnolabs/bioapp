<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

use DataTables;
use URL;
use Carbon\Carbon;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $posts = Post::all();

        return view('app.pages.posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::find($id);

        return view('app.pages.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::find($id);

        return view('app.pages.posts.edit', compact('post'));
    }

    public function new()
    {
        $post = new Post();

        return view('app.pages.posts.new', compact('post'));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $params['comment_status'] = false;
        $params['post_type'] = "post";

        if (!empty($params['published_at'])) {
            $currentTime = Carbon::now()->format('H:i:s');
            $params['published_at'] = Carbon::parse($params['published_at'])->format('Y-m-d').' '.$currentTime;
        }

        $post = new Post($params);

        if (empty($post->seo_title)) {
            $post->seo_title = $params['post_title'];
        }

        if (empty($post->seo_meta_description)) {
            $post->seo_meta_description = $params['post_title'];
        }

        if (empty($post->seo_meta_keywords)) {
            $post->seo_meta_keywords = $params['post_title'];
        }

        if ($post->save()) {
            return redirect()->to(URL::route('app.posts.index'))->with('message.success', "Post save success.");
        } else {
            return redirect()->to(URL::route('app.posts.index'))->with('message.fail', "Post save failed, ".$post->getErrors()->first());
        }
    }

    public function update($id, Request $request)
    {
        $params = $request->all();

        $post = Post::find($id);

        if (!empty($post->published_at)) {
            $oldPublishedDate = Carbon::parse($post->published_at)->format('Y-m-d');
        }

        if (!empty($params['published_at'])) {
            $newPublishedDate = Carbon::parse($params['published_at'])->format('Y-m-d');
        }

        if (!empty($oldPublishedDate) && !empty($newPublishedDate)) {
            $currentTime = Carbon::now()->format('H:i:s');
            $publishedAt = $post->published_at;
            if ($oldPublishedDate != $newPublishedDate) {
                $publishedAt = $params['published_at'];
            }
            $params['published_at'] = Carbon::parse($publishedAt)->format('Y-m-d').' '.$currentTime;
        }

        if ($post->update($params)) {
            return redirect()->to(URL::route('app.posts.index'))->with('message.success', "Post update success.");
        } else {
            return redirect()->to(URL::route('app.posts.index'))->with('message.fail', "Post update failed, ".$post->getErrors()->first());
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
    }

    public function datatables()
    {
        $posts = Post::query();

        return DataTables::eloquent($posts)->toJson();
    }
}
