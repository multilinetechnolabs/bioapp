<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="AnewApp">
    <head>
        @include('partials.shared.meta')
        @include('partials.shared.link_fonts')

        @section('styles')
            <!-- Styles -->
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
            <link href="{{ asset('css/app/affiliate.css') }}" rel="stylesheet">
        @show

        <title>@yield('page-title')</title>
    </head>
    <body>
        @yield('content')
        @section('javascripts')
            @include('partials.shared.foot')
        @show
    </body>
</html>
