@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Blog Posts'}}
@stop
@section('styles')
    @parent

    <style>
        .blogShort { 
            border-bottom:1px solid #ddd;
            padding: 10px 0;
        }

        h6 {
            font-weight: 300;
        }
    </style>
@stop
@section('content')
    @include('partials.header', ['title' => 'Blog Posts'])
@endsection
@section('javascripts')
    @parent

    <div class="container">
        @if($posts->count() < 1)
            <h5>No posts available.</h5>
        @else
            @foreach($posts as $post)
            <div class="col-md-12 blogShort" style="display: inline-block;">
                <h4><a href="{{ route('app.blogs.show', ['slug' => $post->post_slug]) }}">{{ $post->post_title }}</a></h4>
                <h6>Posted on {{ !empty($post->published_at) ? Carbon\Carbon::parse($post->published_at)->format('jS \o\f F, Y g:i:s a') : Carbon\Carbon::parse($post->updated_at)->format('jS \o\f F, Y g:i:s a') }}</h6>
                <div class="row">
                    <div class="col-md-1">
                        <img src="{{ asset('/images/iconimages/load.png') }} " alt="{{ $post->post_title }}" class="pull-left img-responsive thumb margin10 img-thumbnail" style="width: 96px; margin: 8px; ">
                    </div>
                    <div class="col-md-11" style="text-align: justify;">
                        {{ !empty($post->post_excerpt) ? mb_strimwidth($post->post_excerpt, 0, 300, '...') : mb_strimwidth(strip_tags($post->post_content), 0, 300, '...') }}
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
@stop
