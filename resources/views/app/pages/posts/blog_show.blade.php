@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Blog Posts - ' }} {{ $post->post_title }}
@stop
@section('meta-type')
    {{ 'article' }}
@stop
@section('meta-title')
    {{ $post->post_title }}
@stop
@section('meta-description')
    {{ empty($post->seo_title) ? $post->excerpts : $post->seo_title }}
@stop
@section('meta-keywords')
    {{ empty($post->seo_meta_keywords) ? $post->post_title : $post->seo_meta_keywords }}
@stop
@section('meta-url')
    {{ env('APP_URL').'/magnetictherapyblog/'.$post->post_slug }}
@stop
@if (!empty($post->post_featured_img))
    @section('meta-image')
        {{ $post->post_featured_img }}
    @stop
@endif
@section('styles')
    @parent
@stop
@section('content')
    @include('partials.header', ['title' => $post->post_title ])
@endsection
@section('javascripts')
    @parent

    <div class="container">
        
        {!! $post->post_content !!}
    </div>
@stop
