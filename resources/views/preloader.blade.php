@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Home'}}
@stop
@section('styles')
    @parent

    <style>
        html, body, #app, .full-page {
            width: 100%;
            height: 100%;
        }

        body {
            background: url('/images/preloader/background.png') no-repeat fixed;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        img {
            height: 360px;
        }

        @media only screen and (max-width: 767px) {
            img {
                height: 320px;
            }
        }
    </style>
@stop
@section('content')
    <div class="full-page" id="container">
        <h1 style="display: none;">{{ env('APP_TITLE') }}</h1>
        <div class="col-md-12 text-center full-page">
            <div style="height: 31%;">&nbsp;</div>
            <div style="height: 38%;" class="text-center">
            <img src="{{ asset('images/iconimages/load.png') }}" alt="{{ env('APP_TITLE') }}">
            </div>
            <div style="height: 31%;">&nbsp;</div>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        window.onload = init();
        function init() {
            setTimeout("location.href = '/home';", "1500");
        };
    </script>
@stop
