<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

use URL;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::whereDate('published_at', '<=', \Carbon\Carbon::now())-> orderBy('published_at', 'desc')->get();

        return view('app.pages.posts.blog_index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('post_slug', $slug)->first();

        if (!empty($post) && $post->isPublished()) {
            return view('app.pages.posts.blog_show', compact('post'));
        } else {
            return redirect()->to(URL::route('app.blogs.index'))->with('message.fail', 'Blog post not found.');
        }
    }
}
