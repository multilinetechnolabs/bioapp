@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Blog Posts - ' }} {{ $post->post_title }}
@stop
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
